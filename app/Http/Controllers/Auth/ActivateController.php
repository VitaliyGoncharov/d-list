<?php
namespace App\Http\Controllers\Auth;

use DateTime;
use App\Http\Controllers\Controller;
use App\Models\ActivateUser;
use App\Models\User;

class ActivateController extends Controller
{
	public function activate($user_id, $key)
    {
        $user = ActivateUser::where([
            ['user_id', $user_id],
            ['act_key', $key]
        ])->select('user_id', 'act_key', 'send_date')->get();


        // if act_key exists in database and act_key from database matches key from activation link
        if(isset($user[0]->act_key) && $user[0]->act_key == $key) {
            // send_date format: Y-m-d

            // count how many days passed from the day when we sent to user activation link
            $days_have_passed = (new DateTime("now"))->diff(DateTime::createFromFormat('Y-m-d', $user[0]->send_date))->format('%a');

            // if it has passed from 0 to 3 days from the date of sending the message
            if (preg_match('~^[0-2]$~', $days_have_passed)) {

                //remove record from activate_users and update users field `activate` to 1
                ActivateUser::where([
                    ['user_id', $user_id],
                    ['act_key', $key]
                ])->delete();

                User::where([
                    ['active', null],
                    ['id', $user_id]
                ])->update(['active' => 1]);

                // activate user
                $status = 'success';
            }
            // if it has passed more than 3 days
            else {
                $status = 'link_has_expired';
            }

            $link_has_expired = 'Срок действия ссылки истек. Пройдите регистрацию повторно';
            $success = 'Аккаунт подтвержден';
        }
        else
        {
            $status = 'incorrect_key';
            $incorrect_key = 'Неверный ключ активации!';
        }

        return view('auth.activate', [
           'status' => $$status
        ]);
    }
}