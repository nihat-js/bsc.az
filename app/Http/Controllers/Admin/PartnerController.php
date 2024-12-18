<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use DB;
use Illuminate\Http\Request;
use Storage;

class PartnerController extends Controller
{

    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            // 'logo' => 'nullable|file|mimes:jpeg,png,jpg,|max:10240',
            'is_visible' => 'nullable|boolean',
        ]);

        if ($request->has('logo')) {
            $logo = $request->input('logo'); 

            if (!preg_match('/^data:image\/(\w+);base64,/', $logo, $matches)) {
                return response()->json(['error' => 'Invalid base64 image data'], 400);
            }
    
            $imageData = substr($logo, strpos($logo, ',') + 1);
            $mimeType = $matches[1]; 
    
            if (!in_array($mimeType, ['jpeg', 'jpg', 'png'])) {
                return response()->json(['error' => 'Invalid image type. Only jpeg, png, and jpg are allowed.'], 400);
            }
    
            $imageData = base64_decode($imageData);
            if ($imageData === false) {
                return response()->json(['error' => 'Invalid base64 image data'], 400);
            }
            $fileName = time() . '_' . uniqid() . '.' . $mimeType;
    
            $uploadPath = 'uploads/partners';  
            $path = Storage::disk("public")->put($uploadPath . '/' . $fileName, $imageData);
    
            if (!$path) {
                return response()->json(['error' => 'Failed to save image'], 500);
            }
    
            $validated['logo'] = $fileName;
        }


        $partner = Partner::create($validated);

        return response()->json([
            "message" => "ok",
            "data" => $partner
        ], 201);


    }
    public function all()
    {
        $partners = Partner::all();

        $partners->map(function ($partner){
            $partner->logo = asset('uploads/partners/' . $partner->logo);
        });

        return response()->json(["status" => "OK", "data" => $partners]);
    }

    public function one($id)
    {
        $partner = Partner::findOrFail($id);
        return response()->json(["status" => "ok", "data" => $partner]);
    }


    public function edit(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'is_visible' => 'nullable|boolean',
            // 'logo' => 'nullable|file|mimes:jpeg,png,jpg,|max:10240',
            'logo' => 'nullable|string',
        ]);
        $partner = Partner::findOrFail($id);


        if ($request->has('logo')) {
            $logo = $request->input('logo'); 

            if (!preg_match('/^data:image\/(\w+);base64,/', $logo, $matches)) {
                return response()->json(['error' => 'Invalid base64 image data'], 400);
            }
            // return response()->json(['error' => 'Invalid base64 image data'], 400);
    
            $imageData = substr($logo, strpos($logo, ',') + 1);
            $mimeType = $matches[1]; 
    
            if (!in_array($mimeType, ['jpeg', 'jpg', 'png'])) {
                return response()->json(['error' => 'Invalid image type. Only jpeg, png, and jpg are allowed.'], 400);
            }
    
            $imageData = base64_decode($imageData);
            if ($imageData === false) {
                return response()->json(['error' => 'Invalid base64 image data'], 400);
            }
            $fileName = time() . '_' . uniqid() . '.' . $mimeType;
    
            $uploadPath = 'uploads/partners';  

            Storage::disk("public")->delete($uploadPath . '/' . $partner->logo);
            $path = Storage::disk("public")->put($uploadPath . '/' . $fileName, $imageData);
    
            if (!$path) {
                return response()->json(['error' => 'Failed to save image'], 500);
            }
    
            $validated['logo'] = $fileName;
        }



        DB::beginTransaction();
        $partner->update($validated);
        DB::commit();

        return response()->json(["status" => "ok", "data" => $partner]);
    }

    public function delete($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Partner deleted successfully.'
        ]);
    }
}
