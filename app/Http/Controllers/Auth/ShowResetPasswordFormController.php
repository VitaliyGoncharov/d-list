<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PasswordReset;
use DateTime;
use Illuminate\Support\Facades\DB; 

class ShowResetPasswordFormController extends Controller
{
	
	public function show($email, $reset_key)
	{
		$res_record = PasswordReset::where([
			['email', $email],
			['token', $reset_key]
		])->select('email', 'token', 'send_date')->get();

		// check if record exists and for to be sure check if email and token match variables in URL
		if(!empty($res_record[0]) && $res_record[0]->email == $email && $res_record[0]->token == $reset_key)
		{
			$days_have_passed = (new DateTime("now"))->diff(DateTime::createFromFormat('Y-m-d', $res_record[0]->send_date))->format('%a');

			// if link was sent today give access to reset password form
			if(preg_match('~^[0]$~', $days_have_passed))
			{
				return view('auth.passwords.reset', [
					'email' => $email
				]);
			}
			// otherwise, assign $status value of 'link_has_expired'
			else
			{
				$status = 'link_has_expired';
			}	
		}
		else
		{
			$status = 'incorrect_link';
		}

		$link_has_expired = 'Срок действия ссылки истек. Отправьте ссылку сброса пароля повторно';
		$incorrect_link = 'Не верная ссылка!';

		return view('auth.activate', [
			'status' => $$status
		]);

	}
}