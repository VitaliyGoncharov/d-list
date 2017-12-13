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
		$insert = $_FILES['photos']['name'];

		/*$message		= $request->input('message');
		$author_id		= $request->input('author_id');
		$photos			= $_FILES['photos'];
		$attachments	= $request->input('attachments');

		$author = User::where('id',$author_id)->select('id','surname','name','avatar')->get();

		$new_post = [
			'author' 	=> $author[0],
			'message' 	=> $message
		];

		if($photos != null)
		{

			$new_post['photos'] = $photos['name'];
		}

		if($attachments != null)
		{
			$new_post['attachments'] = $attachments;
		}*/

		/*$post = new Post;

		$post->text = $message;
		$post->author_id = $author_id;
		$post->save();*/

		/*$insert = view('addnewpost', [
			'new_post' => $new_post
		]);*/

		echo $insert;

	}
}