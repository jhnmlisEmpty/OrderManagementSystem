<?php

namespace App\Models;

use App\Models\PlatformOrderItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlatformOrder extends Model
{
    use HasFactory;

    protected $fillable = ['platform', 'page', 'full_amount'];

    public function platformOrderItems()
    {
        return $this->hasMany(PlatformOrderItems::class);
    }
}
