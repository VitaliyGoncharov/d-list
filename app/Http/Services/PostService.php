<?php
namespace App\Http\Services;

use App\Models\Post;
use Illuminate\Support\Facades\App;

class PostService
{
    /**
     * PostService constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function get(int $num = 10,$lastPostId = null)
    {
        // $lastPostId is necessary for posts lazy load
        // we get "10" posts with id less than $lastPostId
        $lastPostId = (int) $lastPostId;

        $posts = $this->post->get($num,$lastPostId);

        if(!$posts)
        {
            return false;
        }

        $posts = $this->preparePosts($posts,1);

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

    /**
     * @param $posts
     * @param $comsNum Number of comments to get for each post
     * @return mixed
     */
    public function preparePosts($posts,$comsNum = 1)
    {
        $dateSvc = App::make(DateService::class);
        $attachSvc = App::make(AttachmentService::class);
        $voteSvc = App::make(VoteService::class);
        $commentSvc = App::make(CommentService::class);

        foreach($posts as $post)
        {
            // make post created_at time user-friendly
            // 2000-01-01 00:01:01 => January 1, 2000 at 00:01
            $post->creation_date = $dateSvc->changeDateTime($post->created_at);

            // get the bounded user model (you can find method in Post model)
            $post->author = $post->user();

            $post->attachments = $attachSvc->get($post);
            $post->comments = $commentSvc->getComments($post->id,$comsNum);

            $voteData = $voteSvc->check($post->id);
            $post->liked = $voteData['liked'] ?? false;
            $post->disliked = $voteData['disliked'] ?? false;
        }

        return $posts;
    }
}