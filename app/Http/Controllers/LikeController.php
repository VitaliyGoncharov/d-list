<?php
namespace App\Http\Controllers;

use App\Http\Services\ValidateService;
use App\Models\Dislike;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct(Like $like,ValidateService $validateService)
    {
        $this->like = $like;
        $this->validateService = $validateService;
    }

    public function add()
    {
        // what a pity, it doesn't work in __constructor
        $userId = Auth::user()->id;
        $postId = request()->input('post_id');

        // check if string from client contain only digits (one or more)
        if(!$this->validateService->validatePostId($postId))
            return 'Non-numeric postId';

        // get Dislike model | check if dislike exists
        $this->dislike = resolve(Dislike::class);
        $ifDislikeExists = $this->checkIfDislikeExists($userId,$postId);

        // if dislike exists we need to delete it
        if($ifDislikeExists)
            $this->dislike->deleteByUserIdAndPostId($userId,$postId);

        // add like
        if($this->like->createByUserIdAndPostId($userId,$postId))
            return 'Like was added';
        else
            return 'Can\'t create new record in Like table';
    }

    public function delete()
    {
        $userId = Auth::user()->id;
        $postId = request()->input('post_id');

        if(!$this->validateService->validatePostId($postId))
            return 'Non-numeric postId';

        if($this->like->deleteByUserIdAndPostId($userId,$postId))
            return 'Like was deleted';
        else
            return 'Can\'t delete record from Like table';
    }

    public function checkIfDislikeExists($userId,$postId)
    {
        if($this->dislike->getByUserIdAndPostId($userId,$postId))
            return true;
        else
            return false;
    }
}
