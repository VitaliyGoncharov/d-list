<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrdRequest extends Model
{
    protected $fillable = ['from','to'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function add($from, $to)
    {
        $data = [
            'from' => $from,
            'to' => $to
        ];

        return parent::create($data);
    }

    public function checkIfExists($from, $to)
    {
        return $this->select('from','to')
            ->where([
                ['from', $from],
                ['to', $to]
            ])
            ->first();
    }

    public function getOutgoing($userId)
    {
        return $this->select('from','to')
            ->where('from',$userId)
            ->get();
    }

    public function getIncoming($userId)
    {
        return $this->select('from','to')
            ->where('to',$userId)
            ->get();
    }

    public function deleteRequest($from, $to)
    {
        return $this->where([
            ['from',$from],
            ['to',$to]
        ])->delete();
    }
}
