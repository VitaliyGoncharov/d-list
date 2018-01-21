<?php

namespace App\Http\Controllers;

use Auth;
use DateTime;
use DB;
use App\Comment;
use App\Dislike;
use App\Like;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\LeftMenuController;
use App\Http\Middleware\RedirectIfNotAuthenticated;


class NewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(RedirectIfNotAuthenticated::class);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @param \App\Http\Controllers\LeftMenuController $leftMenuController
     * @param Post $post
     * @param Like $like
     * @param Dislike $dislike
     * @return view ()
     */
    public function index(Request $request, LeftMenuController $leftMenuController)
    {
        // make post created_at time user-friendly
        $posts = $this->changePostDate($this->getPosts(), $request);

        // load profile link from `profile_link` table for left menu
        $leftMenuLinks = $leftMenuController->index();

        // here it's $posts with comments
        $posts = $this->getComments($posts);

        // assign property $post->like or $post->dislike if current user liked or disliked post
        $posts = $this->checkIfUserLikedOrDislikedPost($posts);

        return view('news', [
            'leftMenuLinks' => $leftMenuLinks,
            'posts' => $posts,
        ]);
    }

    public function loadPostsCollection(Request $request, Post $post, Like $like, Dislike $dislike)
    {
        // get last post id on the web page
        $lastPostId = $request->input('lastPostId');

        // make post created_at time user-friendly
        $posts = $this->changePostDate($this->getPosts(10, $lastPostId), $request);

        // here it's $posts with comments
        $posts = $this->getComments($posts);

        // assign property $post->like or $post->dislike if current user liked or disliked post
        $posts = $this->checkIfUserLikedOrDislikedPost($posts);

        return view('post', [
            'posts' => $posts
        ]);
    }

    private function getPosts($num = 10, $lastPostId = null)
    {
        // $lastPostId is necessary for posts lazy load
        // we get "10" posts with id less than $lastPostId
        if ($lastPostId === null)
        {
            $posts = Post::join('users', 'users.id', '=', 'posts.user_id')
                ->join('profile_link', 'posts.user_id', '=', 'profile_link.user_id')
                ->select(
                    'posts.id', 'posts.text', 'posts.user_id', 'posts.photos', 'posts.attachments',
                    'posts.likes', 'posts.dislikes', 'posts.created_at',
                    'users.surname', 'users.name', 'users.avatar',
                    'profile_link.link')
                ->orderBy('id', 'DESC')
                ->take($num)
                ->get();
        }
        else
        {
            $posts = Post::where('posts.id', '<', $lastPostId)
                ->join('users', 'users.id', '=', 'posts.user_id')
                ->join('profile_link', 'posts.user_id', '=', 'profile_link.user_id')
                ->select(
                    'posts.id', 'posts.text', 'posts.user_id', 'posts.photos', 'posts.attachments',
                    'posts.likes', 'posts.dislikes', 'posts.created_at',
                    'users.surname', 'users.name', 'users.avatar',
                    'profile_link.link')
                ->orderBy('id', 'DESC')
                ->take($num)
                ->get();
        }

        // convert json string with photos urls to array
        foreach ($posts as $post)
        {
            $post->photos = json_decode($post->photos);
        }

        // $posts is the Collection which include array of items
        // This works: $posts[0]->id
        // But, VERY STRANGE, end($posts) doesn't contain the last post, it contains array of posts
        $posts_collection = end($posts);
        if ($posts_collection)
        {
            $lastPostId = end($posts_collection)->id;
            $posts->lastPostId = $lastPostId;
        }

        return $posts;
    }

    private function changePostDate($posts, $request)
    {
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

        $hours_to_change_1 = [1, 21];
        $hours_to_change_2 = [2, 3, 4, 22, 23, 24];

        $rus_minutes = [
            'минуту',
            'минуты',
            'минут'
        ];

        $minutes_to_change_1 = [1, 21, 31, 41, 51];
        $minutes_to_change_2 = [2, 3, 4, 22, 23, 24, 32, 33, 34, 42, 43, 44, 52, 53, 54];


        foreach ($posts as $post)
        {
            //get post created_at time
            $date = $post->created_at->format('Y-m-d H:i:s');
            $post_date = DateTime::createFromFormat('Y-m-d H:i:s', $date);

            //get to know the post creating time according user timezone
            $user_utc = $request->session()->get('utc');
            $post_date_for_user = $post_date->modify("+$user_utc hour");

            //get the server date
            date_default_timezone_set('UTC');
            $server_date = new DateTime("+$user_utc hour");

            $post_year = $post_date_for_user->format('Y');
            $post_month = $rus_months[$post_date_for_user->format('n') - 1];
            $post_day = $post_date_for_user->format('j');
            $post_hour = $post_date_for_user->format('H');
            $post_minute = $post_date_for_user->format('i');

            $server_year = $server_date->format('Y');
            $server_month = $server_date->format('m');
            $server_day = $server_date->format('j');

            $diff_days = $post_date_for_user->diff($server_date)->format('%d');
            $diff_hours = $post_date_for_user->diff($server_date)->format('%h');
            $diff_minutes = $post_date_for_user->diff($server_date)->format('%i');

            /**
             * If post was created today
             */
            if ($post_day == $server_day)
            {
                /**
                 * If post was created during current hour
                 */
                if ($diff_hours == 0)
                {
                    if ($diff_minutes != 0)
                    {
                        for ($i = 0; $i < count($minutes_to_change_1); $i++)
                        {
                            if ($diff_minutes == $minutes_to_change_1[$i])
                            {
                                $minutes_word = $rus_minutes[0];
                                break;
                            }
                        }

                        for ($i = 0; $i < count($minutes_to_change_2); $i++)
                        {
                            if ($diff_minutes == $minutes_to_change_2[$i])
                            {
                                $minutes_word = $rus_minutes[1];
                                break;
                            }
                        }

                        if (!isset($minutes_word))
                            $minutes_word = $rus_minutes[2];

                        $creation_date = sprintf('%d %s назад', $diff_minutes, $minutes_word);
                    }

                    /**
                     * If post was created just now
                     */
                    if ($diff_minutes == 0)
                        $creation_date = 'Менее минуты назад';
                }

                /**
                 * If post was created before current hour
                 */
                if ($diff_hours != 0)
                {
                    for ($i = 0; $i < count($hours_to_change_1); $i++)
                    {
                        if ($diff_hours == $hours_to_change_1[$i])
                        {
                            $hours_word = $rus_hours[0];
                            break;
                        }
                    }

                    for ($i = 0; $i < count($hours_to_change_2); $i++)
                    {
                        if ($diff_hours == $hours_to_change_2[$i])
                        {
                            $hours_word = $rus_hours[1];
                            break;
                        }
                    }

                    if (!isset($hours_word))
                    {
                        $hours_word = $rus_hours[2];
                    }

                    $creation_date = sprintf('%d %s назад', $diff_hours, $hours_word);
                    unset($hours_word);
                }
            }

            /**
             * If post was created in this year (time diff was taken into account) AND
             * post was created yesterday OR the day before yesterday
             */
            if ($post_year == $server_year &&
                ($post_day == $server_day - 1 || $post_day == $server_day - 2)
            )
            {
                if ($post_day == $server_day - 1)
                    $creation_date = sprintf('Вчера в %d:%s', $post_hour, $post_minute);
                if ($post_day == $server_day - 2)
                    $creation_date = sprintf('Позавчера в %d:%s', $post_hour, $post_minute);
            }

            /**
             * If post was created in this year (time diff was taken into account) AND
             * post was NOT created today, yesterday OR the day before yesterday
             */
            if ($post_year == $server_year &&
                !($post_day == $server_day || $post_day == $server_day - 1 || $post_day == $server_day - 2)
            )
            {
                // %d:(%s) I use %s because %d prints "6" and %s prints "06" // For ex.: 12:06
                $creation_date = sprintf('%d %s в %d:%s', $post_day, $post_month, $post_hour, $post_minute);
            }

            /**
             * If post wasn't created in this year (time diff was taken into account)
             */
            if ($post_year !== $server_year)
                $creation_date = sprintf('%d %s %d г. в %d:%s', $post_day, $post_month, $post_year, $post_hour, $post_minute);

            /**
             * Save post creation date to the current post object
             */
            $post->creation_date = $creation_date;
        }

        return $posts;
    }

    private function getComments($posts)
    {
        foreach ($posts as $post)
        {
            $comment = Comment::where('post_id', $post->id)
                ->orderBy('created_at', 'DESC')
                ->join('users', 'users.id', '=', 'comments.user_id')
                ->join('profile_link', 'profile_link.user_id', '=', 'comments.user_id')
                ->select('comments.*', 'users.surname', 'users.name', 'users.avatar', 'profile_link.link')
                ->take(1)
                ->get();

            if (!empty($comment[0]))
            {
                $post->comment = $comment[0];
            }
        }

        return $posts;
    }

    private function checkIfUserLikedOrDislikedPost($posts)
    {
        // get the current user id
        $user_id = Auth::user()->id;

        foreach ($posts as $post)
        {
            // when user likes post the record is created in the `likes` table
            // so if he liked post before, then record would exist in `likes` table
            $likes = Like::where([
                ['post_id', $post->id],
                ['user_id', $user_id]
            ])->select('id')->get();

            // if record exists in `likes` table, we mark the post as liked by current user
            if (isset($likes[0]))
            {
                $post->liked = true;
            }
            else
            {
                // if record doesn't exist in `likes` table, maybe user disliked post?
                $dislikes = Dislike::where([
                    ['post_id', $post->id],
                    ['user_id', $user_id]
                ])->select('id')->get();

                // if record exists in `dislikes` table, we mark the post as disliked by current user
                if (isset($dislikes[0]))
                {
                    $post->disliked = true;
                }
            }
        }

        return $posts;
    }
}
