<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dislike extends Model
{
    protected $table = 'dislikes';

    protected $fillable = ['*'];

    public $timestamps = false;
}
