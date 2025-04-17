<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OsrPayslipInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'base_salary',
        'sss',
        'philhealth',
        'pagibig',
        'other_deductions',
        'note_for_deduction', 
        'account_name',
        'account_number',
        'payment_method',
        'qrcode',
        'release_date',
        'payment_proof',
        'status',
        'incentives',
        'start_date',
        'end_date',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
