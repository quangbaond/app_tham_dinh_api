<?php

namespace Database\Seeders;

use App\Models\SettingPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingPeriod::create([
            'title' => '3 tháng',
            'value' => 1,
        ]);

        SettingPeriod::create([
            'title' => '6 tháng',
            'value' => 2,
        ]);

        SettingPeriod::create([
            'title' => '12 tháng',
            'value' => 3,
        ]);

        SettingPeriod::create([
            'title' => '24 tháng',
            'value' => 4,
        ]);

        SettingPeriod::create([
            'title' => '36 tháng',
            'value' => 5,
        ]);

        SettingPeriod::create([
            'title' => '48 tháng',
            'value' => 6,
        ]);

        SettingPeriod::create([
            'title' => '60 tháng',
            'value' => 7,
        ]);

        SettingPeriod::create([
            'title' => '72 tháng',
            'value' => 8,
        ]);

        SettingPeriod::create([
            'title' => '84 tháng',
            'value' => 9,
        ]);

        SettingPeriod::create([
            'title' => '96 tháng',
            'value' => 10,
        ]);

        SettingPeriod::create([
            'title' => '108 tháng',
            'value' => 11,
        ]);

        SettingPeriod::create([
            'title' => '120 tháng',
            'value' => 12,
        ]);
    }
}
