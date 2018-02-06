<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ActivateUser extends Authenticatable
{

    use Notifiable;

    protected $table = 'activate_user';

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