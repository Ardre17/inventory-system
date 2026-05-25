<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'quantity_sent',
        'dispatch_status', 'unit_price', 'subtotal',
        'pallet_number', 'pucho', 'dispatch_expiration_date'
    ];

    protected $casts = [
        'dispatch_expiration_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->dispatch_status) {
            'complete' => '#16a34a',
            'partial'  => '#ea580c',
            'none'     => '#ef4444',
            default    => '#6b7280',
        };
    }

    public function getStatusBgAttribute()
    {
        return match($this->dispatch_status) {
            'complete' => '#dcfce7',
            'partial'  => '#fff7ed',
            'none'     => '#fee2e2',
            default    => '#f3f4f6',
        };
    }
}