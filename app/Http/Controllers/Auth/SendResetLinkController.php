<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Password_resets;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\GenerateKeyController as GenerateKey;
use App\Http\Controllers\Auth\SendMailController as SendMail;

class SendResetLinkController extends Controller
{
	public function send(Request $request, GenerateKey $generateKey, SendMail $sendMail)
	{
		$push_to_pwd_reset = [
                'email' =>  $email = $request->input('email'),
                'token' =>  $reset_key = $generateKey->generate_key(15),
                'send_date' => date('Y-m-d'),
            ];

        $user = Password_resets::updateOrCreate(['email' => $email], $push_to_pwd_reset);

        $status = $sendMail->sendMailAction($email, $reset_key);

        return view('auth.activate', [
        	'status' => $status
        ]);
	}
}