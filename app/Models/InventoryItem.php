<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'inventory_period_id', 'product_id', 'initial_stock',
        'entries', 'exits', 'final_stock', 'physical_count',
        'difference', 'notes'
    ];

    public function period()
    {
        return $this->belongsTo(InventoryPeriod::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
