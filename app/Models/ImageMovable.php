<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageMovable extends Model
{
    use HasFactory;

    protected $table = 'image_movables';
    protected $fillable = [
        'user_movables_id',
        'image',
    ];
}
