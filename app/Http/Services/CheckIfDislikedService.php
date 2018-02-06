<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\ICheckIfDisliked;

class CheckIfDislikedService implements ICheckIfDisliked
{
    public function __construct($dislike)
    {
        $this->dislike = $dislike;
        $this->auth = request()->user();
    }

    public function check(int $postId)
    {
        // get the current user id
        $userId = $this->auth->id;

        // when user dislikes post the record is created in the `dislikes` table
        // so if he disliked post before, then record would exist in `dislikes` table
        $dislikes = $this->dislike->where([
            ['post_id',$postId],
            ['user_id',$userId]
        ])->select('id')->get();

        // if record exists in `dislikes` table, we mark the post as disliked by current user
        if(isset($dislikes[0]))
        {
            $disliked = true;
        }
        else
        {
            $disliked = false;
        }

        return $disliked;
    }
}