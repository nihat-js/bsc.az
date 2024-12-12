<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use DB;
use Illuminate\Http\Request;
use Str;

class PageController extends Controller
{
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
            'image' => 'nullable|file',
            "is_visible" => "nullable|boolean",

            'translations' => 'nullable|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string',
            'translations.*.text' => 'required|string',
            // 'translations.*.slug' => 'required  |string|unique:page_translates,slug|max:255', //add eleyende avtomatik yaradilmasi
        ]);

        $page = Page::findOrFail($id);
        DB::beginTransaction();
        $page->update($validated);

        if ($validated['translations']) {
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


        return response()->json(["message" => "OK", "data" => $page->with("translations")]);
    }
    public function add(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|unique:pages,name",
            "text" => "required|string",
            'image' => 'nullable|file',

            'translations' => 'nullable|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string',
            'translations.*.text' => 'required|string',
            // 'translations.*.slug' => 'required  |string|unique:page_translates,slug|max:255', //add eleyende avtomatik yaradilmasi
        ]);


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
            'message' => 'OK',
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

