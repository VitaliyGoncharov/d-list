<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';

    protected $fillable = ['*'];

    public $timestamps = false;

    public function createByUserIdAndPostId(int $userId,int $postId)
    {
        $this->user_id = $userId;
        $this->post_id = $postId;
        $this->save();

        return true;
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
        $this->where([
            ['user_id',$userId],
            ['post_id',$postId]
        ])->delete();

        return true;
    }
}
