<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\IPost;

class PostService implements IPost
{
    public function __construct($post,$file,$auth)
    {
        $this->post = $post;
        $this->file = $file;
        $this->id = $auth::user()->id;
    }

    public function get(int $num = 10,$lastPostId = false)
    {
        // $lastPostId is necessary for posts lazy load
        // we get "10" posts with id less than $lastPostId
        $posts = $this->post->when($lastPostId, function($query) use ($lastPostId) {
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


        // convert json string with photos urls to array
        foreach($posts as $post)
        {
            // for photos we need only src
            $post->photos = json_decode($post->photos);

            // for attachments we need not only src, we also need filename
            $attachments = json_decode($post->attachments);


            if($attachments)
            {
                $post->attachments = array();
                $attachmentsWithFilename = [];

                foreach($attachments as $attachment)
                {

                    $filename = $this->file->where([
                        ['user_id',$this->id],
                        ['src',$attachment]
                    ])->select('filename')->get();

                    $attachmentWithFilename = [
                        'src' => $attachment,
                        'name' => $filename[0]->filename
                    ];

                    array_push($attachmentsWithFilename,$attachmentWithFilename);
                }
                $post->attachments = $attachmentsWithFilename;

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