<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIdentification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'birthday',
        'name',
        'sex',
        'national',
        'religion',
        'doe',
        'issue_date',
        'image_front',
        'image_back',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
