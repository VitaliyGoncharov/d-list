<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    public function sendMailAction($email, $key, $user_id = null, $name = '', $surname = '')
    {
        $this->email        = $email;
        $this->recipient    = "$name $surname";
        $this->subject      = $user_id ? 'Подтверждение регистрации' : 'Восстановление пароля';
        $this->view         = $user_id ? 'emails.sendActivateKey' : 'emails.sendResetKey';

        $this->actLink = $user_id ? "http://devvit.ru/activate/$user_id/$key" : "http://devvit.ru/password/reset/$email/$key";

        

        Mail::send($this->view, ['link' => $this->actLink], function($message)
        {
            $message->to($this->email, $this->recipient)->subject($this->subject);
        });

        $status = 'Перейдите по ссылке, отправленной на вашу почту: ' . $email;

        return $status;
    }
}
