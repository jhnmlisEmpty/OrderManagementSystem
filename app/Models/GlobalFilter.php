<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalFilter extends Model
{
    use HasFactory;

    protected $fillable = ['filter_name', 'start_date', 'end_date'];
}
