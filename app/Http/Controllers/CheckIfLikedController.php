<?php

namespace App\Http\Controllers;

use Auth;
use App\Like;
use Illuminate\Http\Request;

class CheckIfLikedController extends Controller
{
    public function check($postId)
    {
        // get the current user id
        $userId = Auth::user()->id;

        // when user likes post the record is created in the `likes` table
        // so if he liked post before, then record would exist in `likes` table
        $likes = Like::where([
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
