<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PasswordReset;
use App\User;

class ResetPasswordController extends Controller
{

    public function reset(Request $request)
    {
        $data = array(
            'email' => $email = $request->input('email'),
            'password' => $password = $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation')
        );

        $messages = [
            'password.required' => 'Вы не ввели новый пароль!',
            'password.confirmed' => 'Пароли не совпадают',
            'password.min' => 'Пароль должен быть не менее :min символов',
            'password.string' => 'Пароль можетт состоять из букв латинского алфавита, цифр и знака подчеркивания'
        ];
        
        Validator::make($data, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed'
        ], $messages)->validate();

        PasswordReset::where('email', $email)->delete();

        User::where('email', $email)->update(['password' => bcrypt($password)]);

        $status = 'Пароль успешно изменен!';

        return view('auth.activate', [
            'status' => $status
        ]);
    }
    

    public function __construct()
    {
        $this->middleware('guest');
    }
}
