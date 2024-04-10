<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoanAmount extends Model
{
    use HasFactory;

    protected $table = 'user_loan_amounts';

    protected $fillable = [
        'user_id',
        'khoan_vay',
        'thoi_han_vay',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userHistoryLoanAmounts()
    {
        return $this->hasMany(UserHistoryLoanAmount::class);
    }
}
