<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionOrderItem extends Model
{
    protected $fillable = [
        'production_order_id', 'product_id', 'quantity',
        'sticker', 'precinto', 'label_type', 'sticker_idioma'
    ];

    public function order()
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
