<?php
namespace App\Http\Controllers;

use App\Http\Services\ValidateService;
use App\Models\Dislike;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class DislikeController extends Controller
{
    public function __construct(Dislike $dislike,ValidateService $validateService)
    {
        $this->dislike = $dislike;
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

        // get Like model | check if like exists
        $this->like = resolve(Like::class);
        $ifLikeExists = $this->checkIfLikeExists($userId,$postId);

        // if like exists we need to delete it
        if($ifLikeExists)
            $this->like->deleteByUserIdAndPostId($userId,$postId);

        // add dislike
        if($this->dislike->createByUserIdAndPostId($userId,$postId))
            return 'Dislike was added';
        else
            return 'Can\'t create new record in Like table';
    }

    public function delete()
    {
        $userId = Auth::user()->id;
        $postId = request()->input('post_id');

        if(!$this->validateService->validatePostId($postId))
            return 'Non-numeric postId';

        if($this->dislike->deleteByUserIdAndPostId($userId,$postId))
            return 'Dislike was deleted';
        else
            return 'Can\'t delete record from Like table';
    }

    public function checkIfLikeExists($userId,$postId)
    {
        if($this->like->getByUserIdAndPostId($userId,$postId))
            return true;
        else
            return false;
    }
}
