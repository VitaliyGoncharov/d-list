<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Activate_users extends Authenticatable
{

    use Notifiable;

    protected $fillable = [
        'activate', 'user_id', 'act_key', 'send_date', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $guarded = [
        'created_at'
    ];

    public function getDates()
    {
        return array('created_at');
    }
}