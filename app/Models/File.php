<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
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

    public function get($src)
    {
        $filename = $this
            ->where('src',$src)
            ->select('filename')
            ->first();

        return $filename;
    }

    public function create($data)
    {
        foreach($data as $key => $value)
        {
            $this->{$key} = $value;
        }
        $this->save();
    }
}
