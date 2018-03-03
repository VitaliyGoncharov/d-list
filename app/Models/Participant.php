<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = ['*'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->joined = $model->freshTimestamp();
        });
    }

    public function dialog()
    {
        return $this->belongsToMany(Dialog::class);
    }

    public function checkIfExists($from,$to)
    {
        $this->select('dialog_id')
            ->whereIn(
                'user_id',[$from,$to]
            )
            ->groupBy('dialog_id')
            ->having('dialog_id')
            ->get();
    }
}
