<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfMsgs extends Model
{
    protected $fillable = ['*'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
