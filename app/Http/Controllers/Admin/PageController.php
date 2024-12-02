<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

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

    public function edit()
    {
        $validated = request()->validate([
            // "type" => "required|integer",
            "is_main" => "required|boolean",
            'is_visible' => 'required|boolean',
            'image' => 'nullable|string',

            'translations' => 'nullable|array',
            // 'translations.*.lang_id' => 'required|integer|exists:languages,id', // Validate lang_id
            // 'translations.*.slug' => 'required  |string|unique:page_translates,slug|max:255',
        ]);
        $page = Page::findOrFail(request()->id);

        $page->update([
            // 'type' => $validated['type'],
            'is_main' => $validated['is_main'],
            'is_visible' => $validated['is_visible'],
            'image' => $validated['image'],
        ]);

        if ($page['translations']) {
            foreach ($page['translations'] as $translation) {
                $translation->updateOrCreate(
                    ['lang_id' => $translation['lang_id'],],
                    [
                        'slug' => $translation['slug'],
                        'name' => $translation['name'],
                        'description' => $translation['description'],
                    ]
                );

            }
        }


        return response()->json(["message" => "OK", "data" => $page]);
    }
    public function add(Request $request)
    {
        $validated = $request->validate([
            // "type" => "required|integer",
            "is_main" => "required|boolean",
            'is_visible' => 'required|boolean',
            'image' => 'nullable|file',

            'translations' => 'nullable|array',
            // 'translations.*.lang_id' => 'required|integer|exists:languages,id', // Validate lang_id
            // 'translations.*.slug' => 'required  |string|unique:page_translates,slug|max:255',
        ]);
        // dd($validated);


        $page = Page::create([
            // 'type' => $validated['type'],
            'is_main' => $validated['is_main'],
            'is_visible' => $validated['is_visible'],
            'image' => $validated['image'],
        ]);


        if ($validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                $page->translations()->create(
                    [
                        'lang_id' => $translation['lang_id'],
                        'slug' => $translation['slug'],
                        'name' => $translation['name'],
                        'description' => $translation['description'],
                    ]
                );
            }
        }

        if ($validated["file"]) {
            $file = $validated["file"];
            $file->storeAs("public/pages", $page->id . "." . $file->extension());
        }


        return response()->json([
            'message' => 'OK',
            // 'data' => $page->load('translations'),
        ], 201);
    }

    public function delete()
    {
        $pages = Page::with('translations')->findOrFail(request()->id);

        if (!$pages) {
            return response()->json(['message' => 'NOT_FOUND'], 404);
        }

        foreach ($pages->translations as $translation) {
            $translation->delete();
        }
        $pages->delete();

        return response()->json([
            'message' => 'OK',
            // 'data' => $pages
        ], 200);
        
    }
}

