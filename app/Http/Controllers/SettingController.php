<?php

namespace App\Http\Controllers;

use App\Models\SettingPeriod;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getPeriod(Request $request)
    {
        $periods = SettingPeriod::all();

        return response()->json($periods);
    }
}
