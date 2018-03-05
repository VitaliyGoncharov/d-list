<?php

namespace App\Http\Controllers;

use App\Http\Services\NewPostService;
use App\Models\Post;

class NewPostController extends Controller
{
    public function __construct(Post $post,NewPostService $newPostSvc)
    {
        $this->post = $post;
        $this->newPostSvc = $newPostSvc;
    }

    public function addPost()
    {
        $user_id = request()->user()->id;
        $message = request()->input('message');

        $addPostInfo = $this->newPostSvc->getNewPostInfo();
        $saveToPost = [
            'user_id' => $user_id,
            'text' => $message,
        ];

        if(isset($addPostInfo['files']))
        {
            $attachments = array();

            foreach($addPostInfo['files'] as $file)
            {
                array_push($attachments,$file['src']);
            }

            $saveToPost['files'] = json_encode($attachments);
        }

        if(isset($addPostInfo['images']))
        {
            $photos = $addPostInfo['images'];
            $saveToPost['photos'] = json_encode($photos);
        }

        /**
         * If post has at least one of this below then save the post in DB
         * otherwise exit
         */
        if($message || $photos || $attachments)
        {
            $this->post->create($saveToPost);
        }
        else
        {
            echo 'Nothing to save';
            exit();
        }

        // new post is the last user post
        $post = Post::where('user_id',$user_id)
            ->take(1)
            ->orderBy('id','DESC')
            ->first();

        $user = $post->user()->where('id',$user_id)->first();

        $profileLink = $user->link;

        $posts = [];
        $new_post = (object)[
            'id' => $post->id,
            'author' => (object)[
                'surname' => $user->surname,
                'name' => $user->name,
                'link' => $profileLink,
                'avatar' => $user->avatar
            ],
            'creation_date' => 'Менее минуты назад',
            'text' => $message
        ];

        if(isset($photos) || isset($attachments))
        {
            $new_post->attachments = new \stdClass();
        }

        // if user attached photos to the post then include them in object
        if(isset($photos)) $new_post->attachments->photos = $photos;
        if(isset($attachments)) $new_post->attachments->files = $addPostInfo['files'];

        array_push($posts,$new_post);

        // remove files from session
        request()->session()->forget('news');

        $result = view('post',compact('posts'));

        echo $result;
    }

    public function deleteAttachedPhoto()
    {
        $photo_src = request()->input('src');
        $photos = request()->session()->get('news.addPost.files');

        foreach($photos as $key => $photo)
        {
            if($photo===$photo_src)
            {
                request()->session()->forget('news.addPost.files');
                unset($photos[$key]);
                sort($photos);
                request()->session()->put('news.addPost.files',$photos);
                echo 'Successfully deleted';
                break;
            }
        }
    }

    public function deleteAttachedFile()
    {
        $file_src = request()->input('src');
        $files = request()->session()->get('news.addPost.files');

        foreach($files as $key => $file)
        {
            if($file===$file_src)
            {
                request()->session()->forget('news.addPost.files');
                unset($files[$key]);
                sort($files);
                request()->session()->put('news.addPost.files',$files);
                echo 'Successfully deleted';
                break;
            }
        }
    }
}