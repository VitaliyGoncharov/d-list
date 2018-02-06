<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function get(int $num,int $lastPostId)
    {
        $posts = $this->post
            ->when($lastPostId,function($query) use ($lastPostId) {
                return $query->where('id','<',$lastPostId);
            })
            ->select(
                'id','text','user_id','photos','files',
                'likes','dislikes','created_at')
            ->orderBy('id','DESC')
            ->take($num)
            ->get();

        return $posts;
    }

    public function getPostsWithUsers(int $num,$lastPostId = false)
    {
        $posts = $this->post->when($lastPostId,function($query) use ($lastPostId) {
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

        return $posts;
    }

    public function create($data)
    {
        foreach($data as $key => $value)
        {
            $this->post->{$key} = $value;
        }

        $this->post->save();
    }
}