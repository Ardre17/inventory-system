<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'name', 'description', 'unit', 'stock', 'stock_min',
        'cost', 'supplier_id', 'lot', 'expiration_date', 'image_url', 'active',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'active' => 'boolean',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_raw_materials')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->stock_min;
    }
}
