<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoanAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'khoan_vay',
        'thoi_han_vay',
    ];
}