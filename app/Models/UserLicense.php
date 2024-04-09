<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_card',
        'code',
        'type',
        'class',
        'address',
        'dob',
        'name',
        'date',
        'place_issue',
        'image_front',
        'image_back',
    ];
}
