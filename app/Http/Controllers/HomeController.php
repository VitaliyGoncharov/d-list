<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use DB;
use App\Comment;
use DateTime;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $posts = Post::join('users', 'users.id', '=', 'posts.author_id')
            ->leftjoin('thumbs', 'thumbs.post_id', '=', 'posts.id')
            ->select('posts.*', 'users.surname', 'users.name', 'users.avatar', 'thumbs.thumb')
            ->orderBy('id', 'DESC')
            ->take(10)
            ->get();

        foreach($posts as $post)
        {
            $comment = Comment::where('post_id', $post->id)
                ->orderBy('created_at', 'DESC')
                ->join('users', 'users.id', '=', 'comments.user_id')
                ->select('comments.*', 'users.surname', 'users.name', 'users.avatar')
                ->take(1)
                ->get();

            if(!empty($comment[0]))
            {
                $post->comment = $comment[0];
            }

        }

        $rus_months = [
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

        $rus_hours = [
            'час',
            'часа',
            'часов'
        ];

        $hours_to_change_1 = [1,21];
        $hours_to_change_2 = [2,3,4,22,23,24];

        $rus_minutes = [
            'минуту',
            'минуты',
            'минут'
        ];

        $minutes_to_change_1 = [1,21,31,41,51];
        $minutes_to_change_2 = [2,3,4,22,23,24,32,33,34,42,43,44,52,53,54];


        foreach($posts as $post)
        {
            //get post created_at time
            $date = $post->created_at->format('Y-m-d H:i:s');
            $post_date = DateTime::createFromFormat('Y-m-d H:i:s', $date);

            //get to know the post creating time according user timezone
            $user_utc = $request->session()->get('utc');
            $post_date_for_user = $post_date->modify("+$user_utc hour");

            //get the server time
            date_default_timezone_set('UTC');
            $server_date = new DateTime("+$user_utc hour");


            $post_year      = $post_date_for_user->format('Y');
            $post_month     = $rus_months[$post_date_for_user->format('n')-1];
            $post_day       = $post_date_for_user->format('j');
            $post_hour      = $post_date_for_user->format('H');
            $post_minute    = $post_date_for_user->format('i');

            $server_year    = $server_date->format('Y');
            $server_month   = $server_date->format('m');
            $server_day     = $server_date->format('j');

            $diff_days      = $post_date_for_user->diff($server_date)->format('%d');
            $diff_hours     = $post_date_for_user->diff($server_date)->format('%h');
            $diff_minutes   = $post_date_for_user->diff($server_date)->format('%i');

            if($post_day == $server_day)
            {
                if($diff_hours == 0)
                {
                    if($diff_minutes != 0)
                    {
                        for($i=0;$i<count($minutes_to_change_1);$i++)
                        {
                            if($diff_minutes == $minutes_to_change_1[$i])
                            {
                                $minutes_word = $rus_minutes[0];
                                break;
                            }
                        }

                        for($i=0;$i<count($minutes_to_change_2);$i++)
                        {
                            if($diff_minutes == $minutes_to_change_2[$i])
                            {
                                $minutes_word = $rus_minutes[1];
                                break;
                            }
                        }

                        if(!isset($minutes_word))
                            $minutes_word = $rus_minutes[2];

                        $creation_date = sprintf('%d %s назад', $diff_minutes, $minutes_word);
                    }
                    
                    if($diff_minutes == 0)
                        $creation_date = 'Менее минуты назад';
                }
                
                if($diff_hours != 0)
                {
                    for($i=0;$i<count($hours_to_change_1);$i++)
                    {
                        if($diff_hours == $hours_to_change_1[$i])
                        {
                            $hours_word = $rus_hours[0];
                            break;
                        }
                    }

                    for($i=0;$i<count($minutes_to_change_2);$i++)
                    {
                        if($diff_hours == $minutes_to_change_2[$i])
                        {
                            $hours_word = $rus_hours[1];
                            break;
                        }
                    }

                    if(!isset($hours_word))
                        $hours_word = $rus_hours[2];

                    $creation_date = sprintf('%d %s назад', $diff_hours, $hours_word);
                }
            }

            if($post_year == $server_year &&
                ($post_day == $server_day - 1 || $post_day == $server_day - 2) 
            )
            {
                if($post_day == $server_day - 1)
                    $creation_date = sprintf('Вчера в %d:%d', $post_hour, $post_minute);
                if($post_day == $server_day - 2)
                    $creation_date = sprintf('Позавчера в %d:%d', $post_hour, $post_minute);
            }
            
            if($post_year == $server_year &&
                !($post_day == $server_day - 1 || $post_day == $server_day - 2) 
            )
            {
                $creation_date = sprintf('%d %s в %d:%d', $post_day, $post_month, $post_hour, $post_minute);
            }
            
            if($post_year !== $server_year)
                $creation_date = sprintf('%d %s %d г. в %d:%d', $post_day, $post_month, $post_year, $post_hour, $post_minute);

            $post->creation_date = $creation_date;
        }


        return view('home', [
            'posts' => $posts
        ]);
    }
}
