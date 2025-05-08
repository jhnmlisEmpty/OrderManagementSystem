<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUser extends Model
{
    protected $table = 'product_user'; // Specify the table name

    protected $fillable = [
        'user_id',
        'product_id',
        'price',
    ];

    /**
     * Relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with the Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
