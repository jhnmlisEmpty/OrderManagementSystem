<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incentive extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'page_incentive_percentage',
        'market_incentive_percentage',
        'status',
        'image',
        'release_date',
        'startDate',
        'endDate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
