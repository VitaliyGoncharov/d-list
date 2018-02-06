<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\IDateTime;

class DateTimeService implements IDateTime
{
    public function __construct()
    {
        $this->request = request();
    }

    public function changeDateTime(string $dateTime)
    {
        $monthsWords = [
            'января',
            'февраля',
            'марта',
            'апреля',
            'мая',
            'июня',
            'июля',
            'августа',
            'сентября',
            'октября',
            'ноября',
            'декабря'
        ];

        $hoursWords = [
            'час',
            'часа',
            'часов'
        ];

        $hoursToChange1 = [1,21];
        $hoursToChange2 = [2,3,4,22,23,24];

        $minutesWords = [
            'минуту',
            'минуты',
            'минут'
        ];

        $minutesToChange1 = [1,21,31,41,51];
        $minutesToChange2 = [2,3,4,22,23,24,32,33,34,42,43,44,52,53,54];


        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s',$dateTime);

        //get to know the curDateTime creating time according user timezone
        $user_utc = $this->request->session()->get('utc');
        $oldDateTime = $dateTime->modify("+$user_utc hour");


        //get the server date
        date_default_timezone_set('UTC');
        $serverDateTime = new \DateTime("+$user_utc hour");

        $oldYear = $oldDateTime->format('Y');
        $oldMonth = $monthsWords[$oldDateTime->format('n') - 1];
        $oldDay = $oldDateTime->format('j');
        $oldHour = $oldDateTime->format('H');
        $oldMinute = $oldDateTime->format('i');

        $serverYear = $serverDateTime->format('Y');
        $serverMonth = $serverDateTime->format('m');
        $serverDay = $serverDateTime->format('j');

        $diffDays = $oldDateTime->diff($serverDateTime)->format('%d');
        $diffHours = $oldDateTime->diff($serverDateTime)->format('%h');
        $diffMinutes = $oldDateTime->diff($serverDateTime)->format('%i');

        /**
         * If post was created today
         */
        if($oldDay==$serverDay)
        {
            /**
             * If post was created during current hour
             */
            if($diffHours==0)
            {
                if($diffMinutes!=0)
                {
                    for($i = 0;$i<count($minutesToChange1);$i++)
                    {
                        if($diffMinutes==$minutesToChange1[$i])
                        {
                            $minutes_word = $minutesWords[0];
                            break;
                        }
                    }

                    for($i = 0;$i<count($minutesToChange2);$i++)
                    {
                        if($diffMinutes==$minutesToChange2[$i])
                        {
                            $minutes_word = $minutesWords[1];
                            break;
                        }
                    }

                    if(!isset($minutes_word))
                        $minutes_word = $minutesWords[2];

                    $creation_date = sprintf('%d %s назад',$diffMinutes,$minutes_word);
                }

                /**
                 * If post was created just now
                 */
                if($diffMinutes==0)
                    $creation_date = 'Менее минуты назад';
            }

            /**
             * If post was created before current hour
             */
            if($diffHours!=0)
            {
                for($i = 0;$i<count($hoursToChange1);$i++)
                {
                    if($diffHours==$hoursToChange1[$i])
                    {
                        $hours_word = $hoursWords[0];
                        break;
                    }
                }

                for($i = 0;$i<count($hoursToChange2);$i++)
                {
                    if($diffHours==$hoursToChange2[$i])
                    {
                        $hours_word = $hoursWords[1];
                        break;
                    }
                }

                if(!isset($hours_word))
                {
                    $hours_word = $hoursWords[2];
                }

                $creation_date = sprintf('%d %s назад',$diffHours,$hours_word);
                unset($hours_word);
            }
        }

        /**
         * If post was created in this year (time diff was taken into account) AND
         * post was created yesterday OR the day before yesterday
         */
        if($oldYear==$serverYear &&
            ($oldDay==$serverDay - 1 || $oldDay==$serverDay - 2)
        )
        {
            if($oldDay==$serverDay - 1)
                $creation_date = sprintf('Вчера в %d:%s',$oldHour,$oldMinute);
            if($oldDay==$serverDay - 2)
                $creation_date = sprintf('Позавчера в %d:%s',$oldHour,$oldMinute);
        }

        /**
         * If post was created in this year (time diff was taken into account) AND
         * post was NOT created today, yesterday OR the day before yesterday
         */
        if($oldYear==$serverYear &&
            !($oldDay==$serverDay || $oldDay==$serverDay - 1 || $oldDay==$serverDay - 2)
        )
        {
            // %d:(%s) I use %s because %d prints "6" and %s prints "06" // For ex.: 12:06
            $creation_date = sprintf('%d %s в %d:%s',$oldDay,$oldMonth,$oldHour,$oldMinute);
        }

        /**
         * If post wasn't created in this year (time diff was taken into account)
         */
        if($oldYear!==$serverYear)
            $creation_date = sprintf('%d %s %d г. в %d:%s',$oldDay,$oldMonth,$oldYear,$oldHour,$oldMinute);


        return $creation_date;
    }
}