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

        return response()->json([
            "status" => "ok",
            "data" => $setting
        ], Response::HTTP_CREATED);
    }

    public function one($id)
    {
        $setting = Setting::findOrFail($id);
        return response()->json(["message" => "OK", "data" => $setting]);
    }

    public function oneByKey($key)
    {
        $setting = Setting::where('key', $key)->firstOrFail();
        return response()->json($setting);
    }

    public function edit(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);
        $validatedData = $request->validate([
            'key' => 'sometimes|string|unique:settings,key,' . $id,
            'value' => 'sometimes|string',
        ]);

        $setting->update($validatedData);

        return response()->json(["status" => "ok", "data" => $setting]);
    }


    public function delete($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return response()->json(['message' => 'OK'], Response::HTTP_NO_CONTENT);
    }

    public function bulkUpdate(Request $request)
    {

        $request->validate([
            "data" => "required|array",
        ]);
        // dd($request->all());
        // return $request->all();

        $settings = $request->data;
        // return $settings;
        foreach ($settings as $setting) {
            Setting::where('key', $setting["key"])->update(['value' => $setting["value"]]);
            // return $setting;
        }
        return response()->json(['status' => 'ok', 'message' => 'Settings updated successfully']);
    }
}
