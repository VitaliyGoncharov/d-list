<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Comment;
use App\Models\ProfileLink;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AddCommentController extends Controller
{
    public function addComment(Request $request)
    {
        $text = $request->input('text');
        $user_id = Auth::user()->id;
        $post_id = $request->input('post_id');

        if(!$text || !$post_id || !$user_id) {
            exit();
        }

        $comment = new Comment();
        $comment->comment = $text;
        $comment->user_id = $user_id;
        $comment->post_id = $post_id;
        $comment->save();

        $comments           = [];
        $comment            = (object) [];
        $comment->author    = (object) [];

        $user = User::where('id',$user_id)->select('id','surname','name','avatar')->get();
        $profileLink = ProfileLink::where('user_id',$user_id)->select('link')->get();

        $comment->author->surname   = $user[0]->surname;
        $comment->author->name      = $user[0]->name;
        $comment->author->avatar    = $user[0]->avatar;
        $comment->author->link      = $profileLink[0]->link;
        $comment->comment           = $text;

        array_push($comments,$comment);

        return view('comment', [
           'comments' => $comments
        ]);
    }
}
