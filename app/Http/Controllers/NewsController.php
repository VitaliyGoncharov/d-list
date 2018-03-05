<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Services\NewPostService;
use App\Http\Services\PostService;
use App\Http\Interfaces\Services\ILeftMenu;
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
     * @param PostService $postSvc
     * @param ILeftMenu $ILeftMenu
     * @param NewPostService $newPostSvc
     * @return view ()
     */
    public function index(PostService $postSvc,NewPostService $newPostSvc,ILeftMenu $ILeftMenu)
    {
        $posts = $postSvc->get();

        $leftMenuLinks = $ILeftMenu->getLinks();

        // check if user attached files to the new post which he didn't post
        $newPost = $newPostSvc->getNewPostInfo();

        return view('news.main',compact('leftMenuLinks','posts','newPost'));
    }

    public function renderSearch(PostService $postSvc,ILeftMenu $ILeftMenu)
    {
        $posts = $postSvc->get();

        $leftMenuLinks = $ILeftMenu->getLinks();

        return view('news.search',compact('leftMenuLinks','posts'));
    }

    public function search(Post $post, PostService $postSvc)
    {
        $keyWords = explode(' ', request()->input('keyWords'));

        $posts = $postSvc->preparePosts($post->searchPostsByKeyWords($keyWords));

        foreach ($posts as $post) {
            foreach ($keyWords as $keyWord) {
                $post->text = preg_replace('~('.$keyWord.')~',"<span class=\"highlight\">$1</span>",$post->text);
            }
        }

        return view('post',compact('posts'));
    }

    // load posts when user reach the end of web page
    public function loadPostsCollection(PostService $postSvc)
    {
        // get last post id on the web page
        $lastPostId = request()->input('lastPostId');

        // get posts
        $posts = $postSvc->get(10,$lastPostId);

        // if there are no posts then exit
        if(!isset($posts[0])) {
            exit;
        }

        return view('post',compact('posts'));
    }
}
