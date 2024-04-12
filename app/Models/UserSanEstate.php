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

    protected $casts = ['hinh_anh'=> 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function imageSanEstates()
    {
        return $this->hasMany(ImageSanEstate::class);
    }
}
