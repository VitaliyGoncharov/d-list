<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Middleware\RedirectIfNotAuthenticated;


class NewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware(RedirectIfNotAuthenticated::class);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return view ()
     */
    public function index(Request $request)
    {

        $posts = $this->getPosts();

        foreach($posts as $post)
        {
            $date = $post->created_at->format('Y-m-d H:i:s');

            // make post created_at time user-friendly
            // 2000-01-01 00:01:01 => January 1, 2000 at 00:01
            $post->creation_date = $this->dateTimeController->changeDateTime($date,$request);

            // here it's $posts with comments
            $post->comment = $this->commentController->getComments($post->id);

            // assign property $post->like if current user liked post
            $post->liked = $this->checkIfLikedController->check($post->id);

            // if user didn't like, maybe he disliked
            if(!$post->liked)
                $post->disliked = $this->checkIfDislikedController->check($post->id);
        }

        // load profile link from `profile_link` table for left menu
        $leftMenuLinks = $this->leftMenuController->index();

        // check if user attached files to the new post which he didn't post
        $addPostInfo = $this->getAddPostInfo($request);

        return view('news',[
            'leftMenuLinks' => $leftMenuLinks,
            'posts' => $posts,
            'addPostInfo' => $addPostInfo
        ]);
    }

    // load posts when user reach the end of web page
    public function loadPostsCollection(Request $request)
    {
        // get last post id on the web page
        $lastPostId = $request->input('lastPostId');

        // get posts
        $posts = $this->getPosts(10,$lastPostId);

        // if there are no posts then exit
        if(!isset($posts[0])) {
            exit;
        }

        // make post created_at time user-friendly
        $posts = $this->changePostDate($posts,$request);

        // here it's $posts with comments
        $posts = $this->getComments($posts);

        // assign property $post->like or $post->dislike if current user liked or disliked post
        $posts = $this->checkIfUserLikedOrDislikedPost($posts);

        return view('post',[
            'posts' => $posts
        ]);
    }

    public function getPosts($num = 10,$lastPostId = false)
    {
        // $lastPostId is necessary for posts lazy load
        // we get "10" posts with id less than $lastPostId
        $posts = $this->post->when($lastPostId, function($query) use ($lastPostId) {
                return $query->where('posts.id','<',$lastPostId);
            })
            ->join('users','users.id','=','posts.user_id')
            ->join('profile_link','posts.user_id','=','profile_link.user_id')
            ->select(
                'posts.id','posts.text','posts.user_id','posts.photos','posts.attachments',
                'posts.likes','posts.dislikes','posts.created_at',
                'users.surname','users.name','users.avatar',
                'profile_link.link')
            ->orderBy('id','DESC')
            ->take($num)
            ->get();


        // convert json string with photos urls to array
        foreach($posts as $post)
        {
            // for photos we need only src
            $post->photos = json_decode($post->photos);

            // for attachments we need not only src, we also need filename
            $attachments = json_decode($post->attachments);

            if($attachments)
            {
                $post->attachments = array();
                $attachmentsWithFilename = [];

                foreach($attachments as $attachment)
                {

                    $filename = $this->file->where([
                        ['user_id',Auth::user()->id],
                        ['src',$attachment]
                    ])->select('filename')->get();

                    $attachmentWithFilename = [
                        'src' => $attachment,
                        'name' => $filename[0]->filename
                    ];

                    array_push($attachmentsWithFilename,$attachmentWithFilename);
                }
                $post->attachments = $attachmentsWithFilename;

            }
        }

        // $posts is the Collection which include array of items
        // This works: $posts[0]->id
        // But, VERY STRANGE, end($posts) doesn't contain the last post, it contains array of posts
        $posts_collection = end($posts);
        if($posts_collection)
        {
            $lastPostId = end($posts_collection)->id;
            $posts->lastPostId = $lastPostId;
        }

        return $posts;
    }

    public function getAddPostInfo($request,$addedPost = false)
    {
        if($request->session()->has('news.addPost.files'))
        {
            $filesSession = $request->session()->get('news.addPost.files');
            // $images will contain all images
            // $files won't contain images
            $images = [];
            $files = [];
            $img_extensions = ['jpg','jpeg'];

            for($i = 0;$i<count($filesSession);$i++)
            {
                $filepath = $filesSession[$i];

                // explode filepath on '.'
                $filepath_arr = explode('.',$filepath);

                // extension is the last element in array
                $extension = end($filepath_arr);

                $isImage = false;

                for($j = 0;$j<count($img_extensions);$j++)
                {
                    if($extension==$img_extensions[$j])
                    {
                        $isImage = true;
                        break;
                    }
                }

                if($isImage)
                {
                    array_push($images,$filepath);
                }
                else
                {
                    $filename = $this->file->where([
                        ['user_id',Auth::user()->id],
                        ['src',$filepath]
                    ])->select('filename')->get();


                    $file = [
                        'src' => $filepath,
                        'name' => $filename[0]->filename
                    ];


                    array_push($files,$file);
                }
            }

            return [
                'files' => $files,
                'images' => $images
            ];
        }
    }
}
