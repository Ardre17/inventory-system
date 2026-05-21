<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryPeriod extends Model
{
    protected $fillable = [
        'name', 'start_date', 'end_date', 'status', 'notes', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
