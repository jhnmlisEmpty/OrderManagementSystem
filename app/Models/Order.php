<?php

namespace App\Models;

use App\Models\User;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'payment_method_id',
        'shipping_price',
        'platform',
        'page',
        'tracking',
        'status',
        'date_received',
        'note',
        'total_from_items',
        'full_amount',
        'order_reprocess_date',
        'order_ship_date',
        'order_returned_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
