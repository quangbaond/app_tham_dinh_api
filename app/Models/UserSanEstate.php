<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSanEstate extends Model
{
    use HasFactory;
    protected $table = 'user_san_estates';

    protected $fillable = [
        'user_id',
        'dia_chi',
        'hinh_anh',
        'check'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
