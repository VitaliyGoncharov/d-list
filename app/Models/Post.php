<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Post extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text','photos','attachments'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $guarded = [
        'user_id','created_at'
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

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function like()
    {
        return $this->hasMany(Like::class);
    }

    public function dislike()
    {
        return $this->hasMany(Dislike::class);
    }

    public function get(int $num,int $lastPostId)
    {
        $posts = $this
            ->select(
                'id','text','user_id','photos','files',
                'likes','dislikes','created_at')
            ->when($lastPostId,function($query) use ($lastPostId) {
                return $query->where('id','<',$lastPostId);
            })
            ->orderBy('id','DESC')
            ->take($num)
            ->get();

        return $posts;
    }

    public function getPostById($postId)
    {
        return $this
            ->select(
                'id','text','user_id','photos','files',
                'likes','dislikes','created_at')
            ->where('id',$postId)
            ->first();
    }

    public function updateDislikesNum($postId,$dislikesNum)
    {
        return $this->where('id',$postId)
            ->update(['dislikes' => $dislikesNum]);
    }

    public function updateLikesNum($postId,$likesNum)
    {
        return $this->where('id',$postId)
            ->update(['likes' => $likesNum]);
    }

    public function getPostsWithUsers(int $num,$lastPostId = false)
    {
        $posts = $this->select(
            'posts.id','posts.text','posts.user_id','posts.photos','posts.attachments',
            'posts.likes','posts.dislikes','posts.created_at',
            'users.surname','users.name','users.avatar',
            'profile_link.link')
            ->when($lastPostId,function($query) use ($lastPostId) {
                return $query->where('posts.id','<',$lastPostId);
            })
            ->join('users','users.id','=','posts.user_id')
            ->join('profile_link','posts.user_id','=','profile_link.user_id')
            ->orderBy('id','DESC')
            ->take($num)
            ->get();

        return $posts;
    }

    public function create($data)
    {
        foreach($data as $key => $value)
        {
            $this->{$key} = $value;
        }

        $this->save();
    }

    public function searchPostsByKeyWords($keyWords)
    {
        return $this
            ->select(
                'id','text','user_id','photos','files',
                'likes','dislikes','created_at'
            )
            ->whereRaw("MATCH (text) AGAINST (?)",$keyWords)
            ->get();
    }
}
