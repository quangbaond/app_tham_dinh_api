<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMovables extends Model
{
    use HasFactory;
    protected $table = 'user_movables';

    protected $fillable = [
        'user_id',
        'dia_chi',
        'hinh_anh',
        'loai_tai_san',
        'number_movables',
        'check',
    ];

    protected $casts = ['hinh_anh'=> 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function imageMovables()
    {
        return $this->hasMany(ImageMovable::class, 'user_movables_id', 'id');
    }
}
