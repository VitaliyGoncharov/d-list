<?php
namespace App\Http\Services;

use App\Models\Dislike;
use App\Models\Like;

class VoteService
{
    public function __construct(Like $like, Dislike $dislike)
    {
        $this->like = $like;
        $this->dislike = $dislike;
    }

    public function check(int $postId)
    {
        $userId = request()->user()->id;

        // when user likes post the record is created in the `likes` table
        // so if he liked post before, record would exist in `likes` table
        $likeRec = $this->like->checkIfExists($postId,$userId);
        $dislikeRec = $this->dislike->checkIfExists($postId,$userId);

        $liked = $likeRec ?? false;
        $disliked = $dislikeRec ?? false;

        return [
            'liked' => $liked,
            'disliked' => $disliked
        ];
    }
}