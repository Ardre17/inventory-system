<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'date',
        'status', 'label_type', 'sticker',
        'precinto', 'notes'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ProductionOrderItem::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending'     => '⏳ Pendiente',
            'in_progress' => '🔄 En Proceso',
            'completed'   => '✅ Completada',
            default       => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending'     => '#ea580c',
            'in_progress' => '#2563eb',
            'completed'   => '#16a34a',
            default       => '#374151',
        };
    }
}
