<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = ['user_id','friend_id'];

    public $incrementing = false;
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function add($u1, $u2)
    {
        $data = [
            'user_id' => $u1,
            'friend_id' => $u2
        ];

        return parent::create($data);
    }

    public function get($userId)
    {
        return $this->select('user_id','friend_id')
            ->where('user_id',$userId)
            ->orWhere('friend_id',$userId)
            ->get();
    }
}
