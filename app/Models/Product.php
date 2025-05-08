<?php

namespace App\Models;

use App\Models\OrderItem;
use App\Models\PlatformOrderItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'image',
        'stocks',
        'price',
        'category',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'product_user')
                    ->withPivot('price') // Include the price column
                    ->withTimestamps();
    }

    public function platformOrderItems()
    {
        return $this->hasMany(PlatformOrderItems::class);
    }
}
