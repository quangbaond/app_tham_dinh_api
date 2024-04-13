<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFinance extends Model
{
    use HasFactory;

    protected $fillable = [
        'thu_nhap_hang_thang',
        'ten_cong_ty',
        'dia_chi_cong_ty',
        'so_dien_thoai_cong_ty',
        'point',
        'hinh_anh_sao_ke',
        'check'
    ];

    protected $casts = [
        'hinh_anh_sao_ke' => 'array'
    ];
}
