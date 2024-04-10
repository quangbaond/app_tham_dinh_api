<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHistoryLoanAmount extends Model
{
    use HasFactory;

    protected $table = 'user_history_loan_amounts';

    protected $fillable = [
        'user_loan_amount_id',
        'ngay_tra',
        'so_tien_tra',
        'so_goc_con_no',
        'so_tien_lai',
        'tong_goc_lai',
        'status',
        'status_1',
        'status_2',
        'status_3',
        'note',
    ];

    public function userLoanAmount()
    {
        return $this->belongsTo(UserLoanAmount::class);
    }
}
