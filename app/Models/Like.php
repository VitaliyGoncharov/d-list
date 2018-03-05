<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';

    protected $fillable = ['*'];

    public $timestamps = false;

    public function checkIfExists($postId, $userId)
    {
        return $this->where([
            ['post_id',$postId],
            ['user_id',$userId]
        ])->select('id')->first();
    }

    public function createByUserIdAndPostId(int $userId,int $postId)
    {
        $this->user_id = $userId;
        $this->post_id = $postId;
        return $this->save();
    }

    public function getByUserIdAndPostId(int $userId,int $postId)
    {
        return $this->where([
            ['user_id',$userId],
            ['post_id',$postId]
        ])->first();
    }

    public function deleteByUserIdAndPostId(int $userId,int $postId)
    {
        return $this->where([
            ['user_id',$userId],
            ['post_id',$postId]
        ])->delete();
    }
}
