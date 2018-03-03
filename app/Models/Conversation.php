<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_id1','user_id2'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function checkIfExists($from, $to)
    {
        return $this->select('id')
            ->where([
                ['user_id1', $from],
                ['user_id2', $to]
            ])
            ->orWhere([
                ['user_id1', $to],
                ['user_id2', $from]
            ])
            ->first();
    }

    public function create($from, $to)
    {
        $data = [
            'user_id1' => $from,
            'user_id2' => $to,
        ];

        return parent::create($data);
    }

    public function get($userId)
    {
        return $this->select('user_id1','user_id2')
            ->where([
                ['user_id1',$userId]
            ])
            ->orWhere([
                ['user_id2',$userId]
            ])
            ->get();
    }
}
