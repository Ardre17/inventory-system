<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $fillable = [
        'code',
        'name',
        'stock',
        'minimum_stock',
        'active'
    ];

    public function movements()
    {
        return $this->hasMany(BoxMovement::class);
    }
}
