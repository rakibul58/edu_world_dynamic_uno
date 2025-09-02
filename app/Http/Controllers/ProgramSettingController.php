<?php

namespace App\Http\Controllers;

use App\Models\ProgramSetting;
use Illuminate\Http\Request;

class ProgramSettingController extends Controller
{
    public function index()
    {
        $settings = ProgramSetting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            ProgramSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        return response()->json(['message' => 'Settings updated successfully']);
    }
}
