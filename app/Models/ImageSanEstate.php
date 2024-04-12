<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageSanEstate extends Model
{
    use HasFactory;

    protected $table = 'image_san_estates';

    protected $fillable = [
        'user_san_estates_id',
        'image',
    ];
}
