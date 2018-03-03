<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','surname', 'name', 'email', 'password', 'gender', 'birth', 'phone', 'avatar', 'activate',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function profileLink()
    {
        $profileLink = $this->hasOne(ProfileLink::class)->first();
        return $profileLink->link;
    }

    public function checkIfExists($userId)
    {
        return $this->select('id','surname','name','avatar')
            ->where([
                ['id',$userId],
                ['active',1]
            ])
            ->first();
    }

    public function get($userId)
    {
        return $this->select('id','surname','name','avatar')
            ->where([
                ['id',$userId]
            ])
            ->first();
    }

    public function getUsers($num)
    {
        $userId = Auth::user()->id;

        return $this->select('id','surname','name','avatar')
            ->take($num)
            ->where('id','<>',$userId)
            ->orderBy('id','DESC')
            ->get();
    }

    public function findUsersByExpression($keys, $multiple)
    {
        return $this->select('id','surname','name','birth','phone','avatar')
            ->where('name','like',"$keys[0]%")
            ->when($multiple, function ($query) use ($keys) {
                return $query->orWhere('name','like',"$keys[1]%");
            })
            ->orWhere('surname','like',"$keys[0]%")
            ->when($multiple, function ($query) use ($keys) {
                return $query->orWhere('surname','like',"$keys[1]%");
            })
            ->get();
    }
}
