<?php

namespace App\Providers;

use App\File;
use App\Http\Controllers\CheckIfDislikedController;
use App\Http\Controllers\CheckIfLikedController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DateTimeController;
use App\Http\Controllers\LeftMenuController;
use App\Post;
use App\Dependencies\DNews;
use App\Http\Controllers\NewsController;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind('App\Http\Controllers\NewsController', function ($app) {
            $newsController = new NewsController();

            $newsController->post = new Post();
            $newsController->file = new File();
            $newsController->leftMenuController = new LeftMenuController();
            $newsController->dateTimeController = new DateTimeController();
            $newsController->commentController = new CommentController();
            $newsController->checkIfLikedController = new CheckIfLikedController();
            $newsController->checkIfDislikedController = new CheckIfDislikedController();

            return $newsController;
        });
    }
}
