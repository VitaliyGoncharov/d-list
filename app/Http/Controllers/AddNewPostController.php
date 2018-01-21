<?php

namespace App\Http\Controllers;

use Auth;
use App\Post;
use App\ProfileLink;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AddNewPostController extends Controller 
{
	public function addPost(Request $request,Post $post)
	{
        $user_id		= Auth::user()->id;
		$message		= $request->input('message');
        $photos         = $request->input('files');
		$attachments	= $request->input('attachments');

		// assign properties for Post model
		$post->user_id = $user_id;
		$post->text = $message;
		$post->photos = $photos;
        $post->attachments = $attachments;

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

		$user = User::where(
		    'id',$user_id
        )->select('id','surname','name','avatar')->get();

		$profileLink = ProfileLink::where(
		    'user_id',$user_id
        )->select('link')->get();

		$posts = [];
		$new_post = (object) [];

		$new_post->id = $user[0]->id;
		$new_post->surname = $user[0]->surname;
		$new_post->name = $user[0]->name;
		$new_post->avatar = $user[0]->avatar;
		$new_post->creation_date = 'Менее минуты назад';
		$new_post->text = $message;
        $new_post->link = $profileLink[0]->link;

        // $photos is a json string from the user
        // if user attached photos to the post then convert json string to array
		if($photos != null) $new_post->photos = json_decode($photos);

		array_push($posts,$new_post);

		$result = view('post', [
			'posts' => $posts
		]);

		echo $result;
	}
}