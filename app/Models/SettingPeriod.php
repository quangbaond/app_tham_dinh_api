<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingPeriod extends Model
{
    use HasFactory;

    protected $table = 'setting_periods';

    protected $fillable = [
        'title',
        'value',
    ];
}
