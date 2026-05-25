<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'category_id', 'supplier_id', 'name', 'sku', 'barcode',
    'lot', 'rotation', 'production_date', 'expiration_date',
    'description', 'price', 'cost', 'stock', 'boxes',
    'units_per_box', 'stock_min', 'unit', 'inventory_date',
    'image_url', 'active'
];

    protected $casts = [
        'production_date' => 'date',
        'expiration_date' => 'date',
        'inventory_date'  => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function isLowStock()
    {
        return $this->stock <= $this->stock_min;
    }

    public function getRotationColorAttribute()
    {
        return match($this->rotation) {
            'alta'  => '#16a34a',
            'media' => '#ea580c',
            'baja'  => '#ef4444',
            default => '#374151',
        };
    }
    public function rawMaterials()
{
    return $this->belongsToMany(RawMaterial::class, 'product_raw_materials')
        ->withPivot('quantity')
        ->withTimestamps();
}

public function productionItems()
{
    return $this->hasMany(ProductionOrderItem::class);
}
}