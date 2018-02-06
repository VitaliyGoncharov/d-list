<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\ICheckIfLiked;

class CheckIfLikedService implements ICheckIfLiked
{

    /**
     * CheckIfLikedService constructor.
     * @param $like
     * @param $auth
     */
    public function __construct($like)
    {
        $this->like = $like;
        $this->auth = request()->user();
    }

    /**
     * @param int $postId
     * @return bool
     */
    public function check(int $postId)
    {
        // get the current user id
        $userId = $this->auth->id;

        // when user likes post the record is created in the `likes` table
        // so if he liked post before, then record would exist in `likes` table
        $likes = $this->like->where([
            ['post_id',$postId],
            ['user_id',$userId]
        ])->select('id')->get();

        // if record exists in `likes` table, we mark the post as liked by current user
        if(isset($likes[0]))
        {
            $liked = true;
        }
        else
        {
            $liked = false;
        }

        return $liked;
    }
}