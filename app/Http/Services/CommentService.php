<?php
namespace App\Http\Services;

use App\Models\Comment;

/**
 * @property Comment comment
 */
class CommentService
{
    /**
     * CommentService constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @param int $postId
     * @param int $num
     * @return mixed
     */
    public function getComments(int $postId,int $num)
    {
        $comments = $this->comment->get($postId,$num);

        foreach ($comments as $comment)
        {
            $comment->author = $comment->user();
        }

        return $comments;
    }
}