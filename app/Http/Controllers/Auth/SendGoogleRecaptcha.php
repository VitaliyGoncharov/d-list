<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class SendGoogleRecaptcha extends Controller
{
	public function send($recaptcha)
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		$params = array(
	        'response' => $recaptcha,
	        'secret' => '6LcpYToUAAAAAEYqZJSg7FgYGvEQ2TZzeiy3fSWt',
	        'remoteip' => $ip
      	);

      	$opts = array(
			'http'=>array(
				'method'  => "POST",
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query($params)
			)
      	);

		$context = stream_context_create($opts);
		$responseFromGoogle = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
		
		return json_decode($responseFromGoogle, true);
	}
}