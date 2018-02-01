<?php

namespace App\Http\Controllers;

use Auth;
use App\Dislike;
use Illuminate\Http\Request;

class CheckIfDislikedController extends Controller
{
    public function check($postId)
    {
        // get the current user id
        $userId = Auth::user()->id;

        // when user dislikes post the record is created in the `dislikes` table
        // so if he disliked post before, then record would exist in `dislikes` table
        $dislikes = Dislike::where([
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
