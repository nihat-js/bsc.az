<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SettingController extends Controller
{
    public function all()
    {
        $settings = Setting::all();
        return response()->json($settings);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'required|string',
        ]);

        // Create the new setting
        $setting = Setting::create($validatedData);

        // Return response
        return response()->json($setting, Response::HTTP_CREATED);
    }


    // Update the specified setting
    public function update(Request $request, $id)
    {
        $setting = Setting::find($id);

        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], Response::HTTP_NOT_FOUND);
        }

        // Validate incoming data
        $validatedData = $request->validate([
            'key' => 'required|string|unique:settings,key,' . $id,
            'value' => 'required|string',
        ]);

        // Update the setting
        $setting->update($validatedData);

        return response()->json($setting);
    }


    public function delete($id)
    {
        $setting = Setting::find($id);

        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], Response::HTTP_NOT_FOUND);
        }
        $setting->delete();
        return response()->json(['message' => 'Setting deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
