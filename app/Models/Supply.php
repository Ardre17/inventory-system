<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $fillable = ['code','name','type','variant','stock','stock_min','units_per_roll'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function movements()
    {
        return $this->hasMany(SupplyMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->stock_min;
    }

    public function registerEntry(int $rolls, string $reference = 'Manual', string $notes = ''): void
    {
        $quantity = $rolls * $this->units_per_roll;
        $this->increment('stock', $quantity);
        $this->movements()->create([
            'movement_type' => 'entry',
            'rolls'         => $rolls,
            'quantity'      => $quantity,
            'reference'     => $reference,
            'notes'         => $notes,
        ]);
    }

    public function registerExit(int $quantity, string $reference = '', string $notes = ''): void
    {
        $this->decrement('stock', $quantity);
        $this->movements()->create([
            'movement_type' => 'exit',
            'rolls'         => 0,
            'quantity'      => $quantity,
            'reference'     => $reference,
            'notes'         => $notes,
        ]);
    }
}
