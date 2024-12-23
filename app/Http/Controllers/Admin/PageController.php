<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\ImageUploadService;
use DB;
use Illuminate\Http\Request;
use Storage;
use Str;

class PageController extends Controller
{
    private $uploadPath = "uploads/pages/";
    public function all()
    {
        $pages = Page::with("translations")->get();
        return response()->json(["message" => "OK", "data" => $pages]);
    }

    public function one()
    {
        $page = Page::with("translations")->findOrFail(request()->id);


        return response()->json(["message" => "OK", "data" => $page]);
    }

    public function edit(Request $request, $id)
    {
        $validated = $request->validate([
            "name" => "nullable|string|",
            "slug" => "nullable|string",
            "text" => "nullable|string",
            'image' => 'nullable|string',
            "is_visible" => "nullable|boolean",

            'translations' => 'nullable|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string',
            'translations.*.text' => 'required|string',
            // 'translations.*.slug' => 'required  |string|unique:page_translates,slug|max:255', //add eleyende avtomatik yaradilmasi
        ]);

        

        $page = Page::with("translations")->findOrFail($id);
        if (@$validated["image"]) {
            Storage::disk('public')->makeDirectory($this->uploadPath); // her ehtimala qarsi
            Storage::disk("public")->delete( $this->uploadPath .  $page->getRawOriginal("image"));
            $coverImage = ImageUploadService::uploadBase64Image($validated["image"], $this->uploadPath);
            $validated["image"] = $coverImage;
        }


        DB::beginTransaction();
        $page->update($validated);

        if (@$validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                $translation['slug'] = Str::slug($translation['name']);
                $page->translations()->updateOrCreate(
                    ['lang_code' => $translation['lang_code'],],
                    [
                        'slug' => $translation['slug'],
                        'name' => $translation['name'],
                        'text' => $translation['text'],
                    ]
                );
            }
        }
        DB::commit();


        return response()->json([
            "status" => "ok",
            "data" => $page
        ]);
    }
    public function add(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|unique:pages,name",
            "text" => "required|string",
            'image' => 'nullable|string',
            'is_visible' => 'nullable|boolean',

            'translations' => 'nullable|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string',
            'translations.*.text' => 'required|string',
            // 'translations.*.slug' => 'required  |string|unique:page_translates,slug|max:255', //add eleyende avtomatik yaradilmasi
        ]);

        
        Storage::disk('public')->makeDirectory($this->uploadPath);
        if (@$validated["image"]) {
            $image = ImageUploadService::uploadBase64Image($validated["image"], $this->uploadPath);
            $validated["image"] = $image;
        }


        DB::beginTransaction();

        $validated["slug"] = Str::slug($validated["name"]);
        $page = Page::create($validated);

        if (@$validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                $translation['slug'] = Str::slug($translation['name']);
                $page->translations()->create($translation);
            }
        }
        // if ($validated["file"]) {
        //     $file = $validated["file"];
        //     $file->storeAs("public/pages", $page->id . "." . $file->extension());
        // }

        DB::commit();


        return response()->json([
            'status' => 'ok',
            'data' => $page->load('translations'),
            // 'data' => $page->load('translations'),
        ], 201);
    }

    public function delete()
    {
        $pages = Page::with('translations')->findOrFail(request()->id);

        $pages->translations()->delete();
        $pages->delete();

        return response()->json([
            'message' => 'OK',
            'data' => $pages
        ], 200);

    }
}

