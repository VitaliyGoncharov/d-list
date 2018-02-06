<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/news';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function login(Request $request)
    {

        if(Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'), 
            'active' => 1
        ]))
        {
            $utc = $request->input('utc');
            $request->session()->put('utc', $utc);
            
            return redirect($this->redirectTo);
        }

        $errors = 'Ошибка авторизации';

        return redirect()->back()->with('incorrect_data', 'Неверные почта или пароль!')->with('last.logIn.email',  $request->input('email'));
    }
}
