<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use App\User;

class AddNewPostController extends Controller 
{
	public function addPost(Request $request)
	{	
		$extension = [];
		$posts = [];

		/*for($i=0;$i<count($list_of_files);$i++)
		{
			$filename = explode('.', $list_of_files[$i]);
			$extension[$i] = end($filename);
		}

		print_r($extension);*/

		$message		= $request->input('message');
		$user_id		= $request->input('user_id');
		$photos			= $request->input('files');
		$attachments	= $request->input('attachments');

		$post = new Post;

		$post->user_id = $user_id;
		$post->text = $message;

		if($photos != null)
		{
			$post->photos = $photos;
		}
		if($attachments != null)
		{
			$post->attachments = $attachments;
		}

		$post->save();

		$user = User::where('id',$user_id)->select('id','surname','name','avatar')->get();

		$new_post = (object) [];

		$new_post->id = $user[0]->id;
		$new_post->surname = $user[0]->surname;
		$new_post->name = $user[0]->name;
		$new_post->avatar = $user[0]->avatar;
		$new_post->creation_date = 'Менее минуты назад';
		$new_post->text = $message;

		if($photos != null) $new_post->photos = json_decode($photos);

		array_push($posts,$new_post);

		$result = view('post', [
			'posts' => $posts
		]);

		echo $result;
	}
}