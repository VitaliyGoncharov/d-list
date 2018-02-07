<?php

namespace App\Providers;

use App\Http\Controllers\DislikeController;
use App\Http\Controllers\LikeController;
use App\Http\Interfaces\Services\IAttachment;
use App\Http\Services\AttachmentService;
use App\Models\Comment;
use App\Models\Dislike;
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
use App\Models\Like;
use App\Models\ProfileLink;
use App\Models\User;
use App\Repositories\FileRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Models\Post;
use App\Models\File;
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
            return new PostService(new PostRepository(new Post),new DateTimeService,new AttachmentService(new FileRepository(new File)));
        });

        $this->app->bind(IDateTime::class, function () {
            return new DateTimeService();
        });

        $this->app->bind(IComment::class, function () {
            return new CommentService(new Comment);
        });

        $this->app->bind(ICheckIfLiked::class, function () {
            return new CheckIfLikedService(new Like);
        });

        $this->app->bind(ICheckIfDisliked::class, function () {
            return new CheckIfDislikedService(new Dislike);
        });

        $this->app->bind(ILeftMenu::class, function () {
            return new LeftMenuService(new ProfileLink);
        });

        $this->app->bind(INewPostInfo::class, function () {
            return new NewPostInfoService(new File);
        });

        $this->app->bind(FileRepository::class, function () {
            return new FileRepository(new File);
        });

        $this->app->bind(UserRepository::class, function () {
            return new UserRepository(new User);
        });
    }
}
