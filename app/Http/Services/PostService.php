<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\IDateTime;
use App\Http\Interfaces\Services\IPost;
use App\Http\Interfaces\Services\IAttachment;
use App\Repositories\PostRepository;

class PostService implements IPost
{
    public function __construct(PostRepository $postRepository,IDateTime $IDateTime,IAttachment $IAttachment)
    {
        $this->postRepository = $postRepository;
        $this->IDateTime = $IDateTime;
        $this->IAttachment = $IAttachment;
    }

    public function get(int $num = 10,$lastPostId = null)
    {
        // $lastPostId is necessary for posts lazy load
        // we get "10" posts with id less than $lastPostId
        $lastPostId = (int) $lastPostId;

        $posts = $this->postRepository->get($num,$lastPostId);

        if(!$posts)
        {
//            throw new PostException('no posts');
            return false;
        }

        foreach($posts as $key => $post)
        {
            // make post created_at time user-friendly
            // 2000-01-01 00:01:01 => January 1, 2000 at 00:01
            $beautyDate = $this->IDateTime->changeDateTime($post->created_at);
            $post->creation_date = $beautyDate;

            // get the bounded user model
            $user = $post->user()->first();
            $post->author = $user;

            // get the profileLink model bounded to user model
            // In short, we just get the user profile link
            $post->author->link = $user->profileLink()->first()->link;

            $post->attachments = $this->IAttachment->get($post);

            $post->comment = $post->comment()->take(1)->orderBy('id')->first();

            if(!empty($post->comment))
            {
                $post->comment->author = $post->comment->user()->first();
                $post->comment->author->link = $post->comment->author->profileLink()->first()->link;
            }

            $userId = request()->user()->id;

            // assign property $post->like if current user liked post
            $liked = $post->like()->where('user_id',$userId)->first();

            if(!empty($liked))
            {
                $post->liked = true;
            }
            else
            {
                // if user didn't like, maybe he disliked
                $disliked = $post->dislike()->where('user_id',$userId)->first();

                if(!empty($disliked))
                {
                    $post->disliked = true;
                }
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
}