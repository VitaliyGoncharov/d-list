<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    public function getComments($id,$num = 1)
    {
        $comments = Comment::where('post_id',$id)
            ->orderBy('created_at','DESC')
            ->join('users','users.id','=','comments.user_id')
            ->join('profile_link','profile_link.user_id','=','comments.user_id')
            ->select(
                'comments.id','comments.comment','comments.user_id','comments.post_id',
                'comments.userRep_id','comments.created_at',
                'users.surname','users.name','users.avatar',
                'profile_link.link')
            ->take($num)
            ->get();

        if(!empty($comments[0]))
        {
            $comments = $comments[0];
        }
        else
        {
            $comments = false;
        }

        return $comments;
    }
}
