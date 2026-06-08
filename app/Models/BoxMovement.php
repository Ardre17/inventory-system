<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoxMovement extends Model
{
    protected $fillable = [
        'box_id',
        'user_id',
        'type',
        'quantity',
        'reason',
        'observation'
    ];

    public function box()
    {
        return $this->belongsTo(Box::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}