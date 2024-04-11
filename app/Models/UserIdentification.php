<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIdentification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_card',
        'address',
        'birthday',
        'name',
        'sex',
        'nationality',
        'religion',
        'doe',
        'issue_date',
        'features',
        'image_front',
        'image_back',
        'msbhxh',
        'facebook',
        'zalo',
        'address_now',
        'status',
        'role'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
