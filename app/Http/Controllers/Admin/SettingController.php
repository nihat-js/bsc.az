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

    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'required|string',
        ]);

        $setting = Setting::create($validatedData);

        return response()->json($setting, Response::HTTP_CREATED);
    }

    public function details($key)
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return response()->json(['message' => 'NOT_FOUND'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($setting);
    }

    public function edit(Request $request, $id)
    {
        $setting = Setting::find($id);

        if (!$setting) {
            return response()->json(['message' => 'NOT_FOUND'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'key' => 'required|string|unique:settings,key,' . $id,
            'value' => 'required|string',
        ]);

        $setting->update($validatedData);

        return response()->json(["message" => "OK", $setting]);
    }


    public function delete($id)
    {
        $setting = Setting::find($id);

        if (!$setting) {
            return response()->json(['message' => 'NOT_FOUND'], Response::HTTP_NOT_FOUND);
        }
        $setting->delete();
        return response()->json(['message' => 'OK'], Response::HTTP_NO_CONTENT);
    }
}
