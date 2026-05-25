<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SupplyMovement extends Model
{
    protected $fillable = ['supply_id','movement_type','rolls','quantity','reference','notes'];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
