<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\Services\ICheckIfDisliked;
use App\Http\Interfaces\Services\ICheckIfLiked;
use App\Http\Interfaces\Services\IComment;
use App\Http\Interfaces\Services\IDateTime;
use App\Http\Interfaces\Services\ILeftMenu;
use App\Http\Interfaces\Services\INewPostInfo;
use App\Http\Interfaces\Services\IPost;
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
     * @param IPost $IPost
     * @param IDateTime $IDateTime
     * @param IComment $IComment
     * @param ICheckIfLiked $ICheckIfLiked
     * @param ICheckIfDisliked $ICheckIfDisliked
     * @param ILeftMenu $ILeftMenu
     * @param INewPostInfo $INewPostInfo
     * @return view ()
     */
    public function index(IPost $IPost,IDateTime $IDateTime,IComment $IComment,
                          ICheckIfLiked $ICheckIfLiked,ICheckIfDisliked $ICheckIfDisliked,
                          ILeftMenu $ILeftMenu,INewPostInfo $INewPostInfo)
    {
        $posts = $IPost->get();

        foreach($posts as $post)
        {
            $date = $post->created_at->format('Y-m-d H:i:s');

            // make post created_at time user-friendly
            // 2000-01-01 00:01:01 => January 1, 2000 at 00:01
            $post->creation_date = $IDateTime->changeDateTime($date);

            // here it's $posts with comments
            $post->comment = $IComment->getComments($post->id);

            // assign property $post->like if current user liked post
            $post->liked = $ICheckIfLiked->check($post->id);

            // if user didn't like, maybe he disliked
            if(!$post->liked)
                $post->disliked = $ICheckIfDisliked->check($post->id);
        }

        // load profile link from `profile_link` table for left menu
        $leftMenuLinks = $ILeftMenu->getLinks();

        // check if user attached files to the new post which he didn't post
        $addPostInfo = $INewPostInfo->getNewPostInfo();

        return view('news',[
            'leftMenuLinks' => $leftMenuLinks,
            'posts' => $posts,
            'addPostInfo' => $addPostInfo
        ]);
    }

    // load posts when user reach the end of web page
    public function loadPostsCollection(Request $request,
                                        IPost $IPost,IDateTime $IDateTime,IComment $IComment,
                                        ICheckIfLiked $ICheckIfLiked,ICheckIfDisliked $ICheckIfDisliked)
    {
        // get last post id on the web page
        $lastPostId = $request->input('lastPostId');

        // get posts
        $posts = $IPost->get(10,$lastPostId);

        // if there are no posts then exit
        if(!isset($posts[0])) {
            exit;
        }

        foreach($posts as $post)
        {
            $date = $post->created_at->format('Y-m-d H:i:s');

            // make post created_at time user-friendly
            // 2000-01-01 00:01:01 => January 1, 2000 at 00:01
            $post->creation_date = $IDateTime->changeDateTime($date);

            // here it's $posts with comments
            $post->comment = $IComment->getComments($post->id);

            // assign property $post->like if current user liked post
            $post->liked = $ICheckIfLiked->check($post->id);

            // if user didn't like, maybe he disliked
            if(!$post->liked)
                $post->disliked = $ICheckIfDisliked->check($post->id);
        }

        return view('post',[
            'posts' => $posts
        ]);
    }
}
