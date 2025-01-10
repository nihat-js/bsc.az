<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ImageUploadService;
use DB;
use Illuminate\Http\Request;
use Storage;

class ServiceController extends Controller
{

    private $uploadPath = 'uploads/services/';
    public function all()
    {

        $services = Service::with("translations")->get();

        return response()->json([
            'status' => 'ok',
            'data' => $services
        ]);
    }

    public function one(Request $request, $id)
    {
        $service = Service::with("translations")->findOrFail($id);
        return response()->json([
            'status' => 'ok',
            'data' => $service
        ]);
    }

    public function add(Request $request)
    {

        $request->validate([
            'name' => 'required|string|unique:services,name',
            'cover_image' => 'nullable|string',
            'price' => 'nullable|integer',
            'discounted_price' => 'nullable|integer',
            'text' => 'nullable|string',
            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string|exists:languages,code",
            "translations.*.name" => "required|string",
            "translations.*.description" => "required|string",
            "translations.*.text" => "nullable|string",
        ]);


        DB::beginTransaction();
        $service = Service::create($request->all());

        $uploadPath = 'uploads/services/';
        Storage::disk('public')->makeDirectory($uploadPath);

        if (@$request["cover_image"]) {
            $coverImage = ImageUploadService::uploadBase64Image($request["cover_image"], $uploadPath);
            $service["cover_image"] = $coverImage;
            $service->save();
        }

        if ($request->has('translations')) {
            foreach ($request->translations as $translation) {
                $service->translations()->create($translation);
            }
        }
        DB::commit();
        return response()->json([
            'status' => 'ok',
            'message' => 'Service added successfully',
            'data' => $service
        ]);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:services,name,' . $id,
            'cover_image' => 'nullable|string',
            'price' => 'nullable|integer',
            'discounted_price' => 'nullable|integer',
            'text' => 'nullable|string',
            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string|exists:languages,code",
            "translations.*.name" => "required|string",
            "translations.*.description" => "required|string",
            "translations.*.text" => "nullable|string",
        ]);

        $service = Service::with("translations")->findOrFail($id);
        DB::beginTransaction();



        if (@$request["cover_image"]) {
            Storage::disk('public')->makeDirectory($this->uploadPath);
            Storage::disk("public")->delete($this->uploadPath . $service->getRawOriginal("cover_image"));
            $coverImage = ImageUploadService::uploadBase64Image($request["cover_image"], $this->uploadPath);
            $service->cover_image = $coverImage;
        }

        $service->update($request->all());
        if ($request->has('translations')) {
            foreach ($request->translations as $translation) {
                $service->translations()->updateOrCreate([
                    "lang_code" => $translation['lang_code']
                ], $translation);
            }
        }
        DB::commit();

        return response()->json([
            'status' => 'ok',
            'message' => 'Service updated successfully',
            'data' => $service->load("translations")
        ]);
    }

    public function delete(Request $request, $id)
    {

        $service = Service::with("translations")->findOrFail($id);
        $service->translations()->delete();
        $service->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Service deleted successfully',
            'data' => $service
        ]);
    }
}
