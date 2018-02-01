<?php

namespace App\Providers;

use App\Comment;
use App\Dislike;
use App\Http\Interfaces\Services\ICheckIfDisliked;
use App\Http\Interfaces\Services\ICheckIfLiked;
use App\Http\Interfaces\Services\IComment;
use App\Http\Interfaces\Services\IDateTime;
use App\Http\Interfaces\Services\ILeftMenu;
use App\Http\Interfaces\Services\INewPostInfo;
use App\Http\Interfaces\Services\IPost;
use App\Http\Services\CheckIfDislikedService;
use App\Http\Services\CheckIfLikedService;
use App\Http\Services\CommentService;
use App\Http\Services\DateTimeService;
use App\Http\Services\LeftMenuService;
use App\Http\Services\NewPostInfoService;
use App\Http\Services\PostService;
use App\Like;
use App\ProfileLink;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Post;
use App\File;
use Illuminate\Support\Facades\Auth;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IPost::class, function () {
            return new PostService(new Post,new File,new Auth);
        });

        $this->app->bind(IDateTime::class, function () {
            $request = $this->app->make(Request::class);
            return new DateTimeService($request);
        });

        $this->app->bind(IComment::class, function () {
            return new CommentService(new Comment);
        });

        $this->app->bind(ICheckIfLiked::class, function () {
            return new CheckIfLikedService(new Like,new Auth);
        });

        $this->app->bind(ICheckIfDisliked::class, function () {
            return new CheckIfDislikedService(new Dislike,new Auth);
        });

        $this->app->bind(ILeftMenu::class, function () {
            return new LeftMenuService(new ProfileLink,new Auth);
        });

        $this->app->bind(INewPostInfo::class, function () {
            $request = $this->app->make(Request::class);
            return new NewPostInfoService($request,new File,new Auth);
        });
    }
}
