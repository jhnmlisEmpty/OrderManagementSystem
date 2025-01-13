<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlatformOrderItems extends Model
{
    //
    use HasFactory;

    protected $fillable = ['platform_order_id', 'product_id', 'quantity', 'price', "total_amount"];

    public function platformOrder()
    {
        return $this->belongsTo(PlatformOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
