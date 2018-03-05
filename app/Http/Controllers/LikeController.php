<?php
namespace App\Http\Controllers;

use App\Http\Services\ValidateService;
use App\Models\Dislike;
use App\Models\Like;
use App\Models\Post;

/**
 * @property Like like
 * @property Dislike dislike
 * @property Post post
 */
class LikeController extends Controller
{
    /**
     * LikeController constructor.
     * @param Like $like
     * @param Dislike $dislike
     * @param Post $post
     */
    public function __construct(Like $like,Dislike $dislike,Post $post)
    {
        $this->like = $like;
        $this->dislike = $dislike;
        $this->post = $post;
    }

    /**
     * @param ValidateService $validateSvc
     * @return string
     */
    public function add(ValidateService $validateSvc)
    {
        $userId = request()->user()->id;
        $postId = request()->input('post_id');

        // check if string from client contain only digits (one or more)
        if(!$validateSvc->validatePostId($postId))
            return 'Non-numeric postId';

        $dislikeExists = $this->checkIfDislikeExists($userId,$postId);

        $postRec = $this->post->getPostById($postId);

        // if dislike exists we need to delete it
        if($dislikeExists)
        {
            if ($this->dislike->deleteByUserIdAndPostId($userId,$postId))
                $this->decrementDislikesNum($postRec);
        }

        // add like
        if ($this->like->createByUserIdAndPostId($userId,$postId))
            $likesNum = $this->incrementLikesNum($postRec);

        return $likesNum;
    }

    public function delete(ValidateService $validateSvc)
    {
        $userId = request()->user()->id;
        $postId = request()->input('post_id');

        if(!$validateSvc->validatePostId($postId))
            return 'Non-numeric postId';

        $postRec = $this->post->getPostById($postId);

        if ($this->like->deleteByUserIdAndPostId($userId,$postId))
            $likesNum = $this->decrementLikesNum($postRec);

        return $likesNum;
    }

    /**
     * @param $userId
     * @param $postId
     * @return bool
     */
    public function checkIfDislikeExists($userId,$postId)
    {
        $exists = $this->dislike->checkIfExists($userId,$postId);

        return $exists;
    }

    public function decrementLikesNum($postRec)
    {
        $likesNum = $postRec->likes;
        $likesNum--;

        $this->post->updateLikesNum($postRec->id,$likesNum);
        return $likesNum;
    }

    public function decrementDislikesNum($postRec)
    {
        $dislikesNum = $postRec->dislikes;
        $dislikesNum--;

        $this->post->updateDislikesNum($postRec->id,$dislikesNum);
    }

    public function incrementLikesNum($postRec)
    {
        $likesNum = $postRec->likes;
        $likesNum++;

        $this->post->updateLikesNum($postRec->id,$likesNum);
        return $likesNum;
    }
}
