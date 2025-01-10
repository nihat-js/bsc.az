<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Storage;




class LanguageController
{
    private $uploadPath = 'uploads/languages/';
    public function all()
    {
        $languages = Language::all();
        return response()->json($languages, 200);
    }


    public function add(Request $request)
    {
        $validated = $request->validate([
            'is_visible' => 'required|boolean',
            'code' => 'required|string|unique:languages,code|max:2',
            'name' => 'required|string|max:255',
            "image" => "sometimes|string",
        ]);

        if (@$request["image"]) {
            Storage::disk('public')->makeDirectory($this->uploadPath);
            $coverImage = ImageUploadService::uploadBase64Image($request["image"], $this->uploadPath);
            $validated["image"] = $coverImage;
        }

        // return response()->json(["status" => "ok", "data" => $request->all()], 201);
        $language = Language::create($validated);

        return response()->json(["status" => "ok", "data" => $language,], 201);
    }

    public function one($id)
    {
        $language = Language::findOrFail($id);

        return response()->json(["status" => "ok", "data" => $language], 200);
    }

    public function oneByKey($key)
    {
        $language = Language::where("key", $key)->first();

        return response()->json(["status" => "ok", "data" => $language], 200);
    }

    public function edit(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $validated = $request->validate([
            'is_visible' => 'sometimes|boolean',
            'code' => 'sometimes|string|unique:languages,code,' . $id . '|max:2',
            'name' => 'sometimes|string|max:255',
            "image" => "sometimes|string",
        ]);


        if (@$validated["image"]) {
            Storage::disk("public")->delete($this->uploadPath . $language->getRawOriginal("image"));
            $image = ImageUploadService::uploadBase64Image($request["image"], $this->uploadPath);
            $validated["image"] = $image;
        }
        $language->update($validated);

        return response()->json(["status" => "ok", "data" => $language], 200);
    }

    public function delete($id)
    {
        $language = Language::findOrFail($id);

        if ($language->getRawOriginal("image")) {
            Storage::disk("public")->delete($this->uploadPath . $language->getRawOriginal("image"));
        }
        $language->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Language deleted successfully',
            "data" => $language
        ], 200);
    }
}
