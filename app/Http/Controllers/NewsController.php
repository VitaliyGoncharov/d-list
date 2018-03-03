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
     * @param ILeftMenu $ILeftMenu
     * @param INewPostInfo $INewPostInfo
     * @return view ()
     */
    public function index(IPost $IPost,ILeftMenu $ILeftMenu,INewPostInfo $INewPostInfo)
    {
        $posts = $IPost->get();

        // load profile link from `profile_link` table for left menu
        $leftMenuLinks = $ILeftMenu->getLinks();

        // check if user attached files to the new post which he didn't post
        $addPostInfo = $INewPostInfo->getNewPostInfo();

        return view('news.main',compact('leftMenuLinks','posts','addPostInfo'));
    }

    public function renderSearch(IPost $IPost,ILeftMenu $ILeftMenu)
    {
        $posts = $IPost->get();

        // load profile link from `profile_link` table for left menu
        $leftMenuLinks = $ILeftMenu->getLinks();

        return view('news.search',compact('leftMenuLinks','posts'));
    }

    // load posts when user reach the end of web page
    public function loadPostsCollection(IPost $IPost)
    {
        // get last post id on the web page
        $lastPostId = request()->input('lastPostId');

        // get posts
        $posts = $IPost->get(10,$lastPostId);

        // if there are no posts then exit
        if(!isset($posts[0])) {
            exit;
        }

        return view('post',compact('posts'));
    }
}
