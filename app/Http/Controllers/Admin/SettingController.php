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

    public function one($id)
    {
        $setting = Setting::findOrFail($id);
        return response()->json(["message" => "OK", "data" => $setting]);
    }

    public function oneByKey($key){
        $setting = Setting::where('key', $key)->firstOrFail();
        return response()->json($setting);
    }

    public function edit(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);



        $validatedData = $request->validate([
            'key' => 'required|string|unique:settings,key,' . $id,
            'value' => 'required|string',
        ]);

        $setting->update($validatedData);

        return response()->json(["message" => "OK", $setting]);
    }


    public function delete($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return response()->json(['message' => 'OK'], Response::HTTP_NO_CONTENT);
    }

    public function bulkUpdate(Request $request){

        // $request->validate(
        //     [
        //         // "settings" => "required|array",
        //     ]
        //     );
        // dd($request->all());
        // return $request->all();

        $settings = $request->all();
        // return $settings;
        foreach ($settings as $setting){
            Setting::where('key', $setting["key"])->update(['value' => $setting["value"]]);
            // return $setting;
        }
        return response()->json(['status' => 'OK']);
    }
}
