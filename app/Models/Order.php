<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'type', 'order_type', 'status',
        'client_supplier', 'client_order_number', 'barcode',
        'subtotal', 'tax', 'total', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getOrderTypeLabelAttribute()
    {
        return match($this->order_type) {
            'local'        => '🏪 Local',
            'encomienda'   => '📦 Encomienda',
            'supermercado' => '🛒 Supermercado',
            default        => $this->order_type,
        };
    }

    public function getOrderTypeColorAttribute()
    {
        return match($this->order_type) {
            'local'        => '#2563eb',
            'encomienda'   => '#ea580c',
            'supermercado' => '#16a34a',
            default        => '#374151',
        };
    }

    public function getDispatchStatusAttribute()
    {
        $items = $this->items;
        if ($items->isEmpty()) return 'pending';
        if ($items->every(fn($i) => $i->dispatch_status === 'complete')) return 'complete';
        if ($items->every(fn($i) => $i->dispatch_status === 'none')) return 'none';
        return 'partial';
    }
}