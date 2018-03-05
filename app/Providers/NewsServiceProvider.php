<?php

namespace App\Providers;

use App\Http\Controllers\ChatController;
use App\Http\Services\AttachmentService;
use App\Models\Conversation;
use App\Http\Interfaces\Services\ILeftMenu;
use App\Http\Services\LeftMenuService;
use App\Http\Services\PostService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use App\Models\Post;
use App\Models\File;

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
        $this->app->bind(ILeftMenu::class,function() {
            return new LeftMenuService();
        });

        $this->app->bind(ChatController::class,function() {
            return new ChatController(App::make(ILeftMenu::class),new Conversation());
        });

        $this->app->bind(PostService::class,function() {
            return new PostService(new Post);
        });

        $this->app->bind(AttachmentService::class,function() {
            return new AttachmentService(new File);
        });
    }
}
