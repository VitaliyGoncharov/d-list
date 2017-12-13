<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\User;

class CheckUserInputController extends Controller
{
	public function checkUserInput(Request $request)
	{
        $ids = [
            'name'          => 'name',
            'surname'       => 'surname',
            'reg_email'     => 'email',
            'reg_password'  => 'password'
        ];

        $rules = array(
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
                'regex:~^[ \w\d !@#?%&+=;:,./   \- \* \( \) \^ \| \~ \{\} \$ \[ \] ]+$~',
                'string',
                'min:6',
                'max:40',   
            ]
        );

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


        foreach($ids as $key => $value)
        {
            if($request->has($key))
            {
                $input_for_validation = $value;
                $input_value = $request->input($key);
                break;
            }
        }

        $dataForValidator = [
            $input_for_validation => $input_value
        ];

        
        $validator = Validator::make($dataForValidator, [
            $input_for_validation => $rules[$input_for_validation]
        ], $messages);

        if($validator->fails())
        {
            $errors = $validator->errors();
            echo $errors->first($input_for_validation);
            exit;
        }
        else
        {
            echo 'success';
        }
        
    }
}