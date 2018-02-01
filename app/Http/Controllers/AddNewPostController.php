<?php

namespace App\Http\Controllers;

use Auth;
use App\Post;
use App\ProfileLink;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NewsController;


class AddNewPostController extends Controller 
{
	public function addPost(Request $request,Post $post,NewsController $newsController)
	{
        $user_id		= Auth::user()->id;
		$message		= $request->input('message');

        $addPostInfo = $newsController->getAddPostInfo($request,true);
        $photos = $addPostInfo['images'];
        $attachments = array();

        if($addPostInfo['files'])
        {
            foreach($addPostInfo['files'] as $file)
            {
                array_push($attachments,$file['src']);
            }
        }

        // assign properties for Post model
		$post->user_id = $user_id;
		$post->text = $message;
		$post->photos = json_encode($photos);
        $post->attachments = json_encode($attachments);

        /**
         * If post has at least one of this below then save the post in DB
         * otherwise exit
         */
		if($message || $photos || $attachments) {
            $post->save();
        }
        else {
		    echo 'Nothing to save';
            exit();
        }

        $post = Post::where('user_id',$user_id)
            ->take(1)
            ->orderBy('id','DESC')
            ->select('id')
            ->get();

		$user = User::where(
		    'id',$user_id
        )->select('id','surname','name','avatar')->get();

		$profileLink = ProfileLink::where(
		    'user_id',$user_id
        )->select('link')->get();

		$posts = [];
		$new_post = (object) [
		    'id' => $post[0]->id,
            'surname' => $user[0]->surname,
            'name' => $user[0]->name,
            'avatar' => $user[0]->avatar,
            'creation_date' => 'Менее минуты назад',
            'text' => $message,
            'link' => $profileLink[0]->link
        ];

        // if user attached photos to the post then include them in object
		if($photos) $new_post->photos = $photos;
		if($attachments) $new_post->attachments = $addPostInfo['files'];

		array_push($posts,$new_post);

		// remove files from session
        $request->session()->forget('news');

		$result = view('post', [
			'posts' => $posts
		]);

		echo $result;
	}

	public function deleteAttachedPhoto(Request $request)
    {
        $photo_src = $request->input('src');
        $photos = $request->session()->get('news.addPost.files');

        foreach($photos as $key => $photo)
        {
            if($photo === $photo_src)
            {
                $request->session()->forget('news.addPost.files');
                unset($photos[$key]);
                sort($photos);
                $request->session()->put('news.addPost.files',$photos);
                echo 'Successfully deleted';
                break;
            }
        }
    }

    public function deleteAttachedFile(Request $request)
    {
        $file_src = $request->input('src');
        $files = $request->session()->get('news.addPost.files');

        foreach($files as $key => $file)
        {
            if($file === $file_src)
            {
                $request->session()->forget('news.addPost.files');
                unset($files[$key]);
                sort($files);
                $request->session()->put('news.addPost.files',$files);
                echo 'Successfully deleted';
                break;
            }
        }
    }
}