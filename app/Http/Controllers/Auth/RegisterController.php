<?php

namespace App\Http\Controllers\Auth;

use App\Activate_users;
use App\User;
use DateTime;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Auth\GenerateKeyController as GenerateKey;
use App\Http\Controllers\Auth\SendMailController as SendMail;
use App\Http\Controllers\Auth\SendGoogleRecaptcha as SendRecaptcha;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
          'email.unique'    => 'Пользователь с таким email занят',
          'email.regex'     => 'Неверный формат почты',
          'name.min'        => 'Имя должно быть не менее :min символов',
          'name.max'        => 'Имя должно быть не более :max символов',
          'surname.min'     => 'Фамилия должна быть не менее :min символов',
          'surname.max'     => 'Фамилия должна быть не более :max символов',
          'surname.regex'   => 'Допустимы символы A-Z,a-z,А-Я,а-я',
          'name.regex'      => 'Допустимы символы A-Z,a-z,А-Я,а-я',
          'required'        => 'Поле обязательно для заполнения!',
          'password.min'    => 'Пароль должен быть не менее :min символов',
          'password.max'    => 'Пароль должен быть не более :max символов',
          'password.regex'  => 'Допустимы символы A-Z,a-z и спец.символы !@#$%^&*()-_+=;:,./?\|`~{}]+$',
          'captcha.regex'   => 'Подтвердите, что вы не бот!'
        ];

        return Validator::make($data, [
            'surname'   => 'required|string|min:2|max:15|regex:~^[\w]{2,15}$~u',
            'name'      => 'required|string|min:2|max:15|regex:~^[\w]{2,15}$~u',
            'email'     => [
                'required',
                'string',
                'regex:~^([\w\d]{4,31})@([\w]{1,15})\.([\w]{1,15})$~u',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('active', 1);
                })
            ],
            'password'  => [
                'required',
                'string',
                'min:6',
                'max:40',
                'regex:~^[ \w\d !@#?%&+=;:,./   \- \* \( \) \^ \| \~ \{\} \$ \[ \] ]{6,40}$~'
            ],
            'day'       => 'required',
            'month'     => 'required',
            'year'      => 'required',
            'gender'    => 'required',
            'captcha'   => 'required',
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $birth = $data['year'] . '-' . $data['month'] . '-' . $data['day'];

        $push_to_user = [
            'surname' => $data['surname'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'gender' => $data['gender'],
            'birth' => $birth,
            'avatar' => 'http://dontfear.ru/wp-content/uploads/2014/07/4a0bf547e4d045c0f0557d33cd839f0b-300x300.png',
        ];

        $user = User::updateOrCreate(['email' => $data['email'], 'active' => null], $push_to_user);

        $push_to_activate_users = [
            'user_id' => $user->id,
            'act_key' => $data['act_key'],
            'send_date' => $data['send_date']
        ];

        Activate_users::updateOrCreate(['user_id' => $user->id], $push_to_activate_users);

        return $user;
    }


    public function register(Request $request, GenerateKey $generateKey, SendMail $sendMail, SendRecaptcha $sendRecaptcha)
    {

      $day = $request->input('day');
      $month = $request->input('month');
      $year = $request->input('year');

      //> Google recaptcha
      $recaptcha = $request->input('g-recaptcha-response');
      $jsonFromGoogle = $sendRecaptcha->send($recaptcha);
      $successStatus = $jsonFromGoogle['success'];
      if($jsonFromGoogle['success'] === true)
          $successStatus = 1;
      else
          $successStatus = null;
      //<

      ///////////////////////////
      $act_key = $generateKey->generate_key(15);
      ///////////////////////////

      //#35e312

      // set server time to utc
      date_default_timezone_set('UTC');

      $this->data = array(
        'name'      => $request->input('name'),
        'surname'   => $request->input('surname'),
        'email'     => $request->input('email'),
        'password'  => $request->input('password'),
        'gender'    => $request->input('gender'),
        'day'       => $day,
        'month'     => $month,
        'year'      => $year,
        'act_key'   => $act_key,
        'send_date' => date('Y-m-d'),
        'captcha'   => $successStatus
      );

      $validator = $this->validator($this->data);

      if($validator->fails())
      {
        $redirectWith = array(
          'last.name'       => $request->input('name'),
          'last.surname'    => $request->input('surname'),
          'last.email'      => $request->input('email'),
          'last.gender'     => $request->input('gender'),
          'last.day'        => $day,
          'last.month'      => $month,
          'last.year'       => $year,
        );
        
        foreach($redirectWith as $key => $value)
        {
          $request->session()->flash($key, $value);  
        }
        
        return back()->withErrors($validator, 'register');
      }

      //event(new Registered($user = $this->create($this->data)));
      $user = $this->create($this->data);

      $status = $sendMail->sendMailAction(
          $request->input('email'),
          $act_key,
          $user->id,                
          $request->input('name'),
          $request->input('surname')
      );


      return view('auth.activate', [
          'status' => $status
      ]);

    }
}
