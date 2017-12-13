<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class ShowSendEmailFormController extends Controller
{
	public function show()
	{
		return view('auth.passwords.email');
	}

	public function __construct()
    {
        $this->middleware('guest');
    }
}