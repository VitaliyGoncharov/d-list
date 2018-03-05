<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Comment extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class)->first();
    }

    public function get(int $postId, int $num)
    {
        return $this->select('id','text','user_id','userRep_id','created_at')
            ->where('post_id',$postId)
            ->orderBy('id','DESC')
            ->take($num)
            ->get();
    }
}
