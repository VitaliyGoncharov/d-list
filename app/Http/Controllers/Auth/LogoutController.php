<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
    	$user_email = Auth::user()->email;

        $request->session()->flush();

        Auth::logout();

        $request->session()->put('input.old', $user_email);

        return redirect()->route('home');
    }
}
