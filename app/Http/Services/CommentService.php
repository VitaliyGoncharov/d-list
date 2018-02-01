<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\IComment;

class CommentService implements IComment
{
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function getComments(int $id,int $num = 1)
    {
        $comments = $this->comment->where('post_id',$id)
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