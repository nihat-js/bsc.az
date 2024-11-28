<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function all()
    {
        $pages = Page::all();

        return response()->json(["message" => "OK", "data" => $pages]);
    }

    public function details()
    {
        $page = Page::find(request()->id);

        if (!$page) {
            return response()->json(["message" => "Page not found"], 404);
        }

        return response()->json(["message" => "OK", "data" => $page]);
    }

    public function edit()
    {
        $page = Page::find(request()->id);

        if (!$page) {
            return response()->json(["message" => "Page not found"], 404);
        }

        return response()->json(["message" => "OK", "data" => $page]);
    }
    public function add(Request $request)
    {
        $validated = $request->validate([
            "type" => "required|integer",
            "is_main" => "required|boolean",
            'is_visible' => 'required|boolean',
            'image' => 'nullable|string',

            'translations' => 'nullable|array',
            // 'translations.*.lang_id' => 'required|integer|exists:languages,id', // Validate lang_id
            // 'translations.*.slug' => 'required  |string|unique:page_translates,slug|max:255',
        ]);
        // dd($validated);


        // $page = Page::create([
        //     'type' => $validated['type'],
        //     'is_main' => $validated['is_main'],
        //     'is_visible' => $validated['is_visible'],
        //     'image' => $validated['image'],
        // ]);


        $translationsData = [];
        if (@$validated["translations"]) {
            foreach ($validated["translations"] as $t) {
                if ($t["slug"]) {
                    $translationsData[] = [
                        'lang_id' => $t['lang_id'],
                        'slug' => $t['slug'],
                        'title' => $t['title'],
                        'description' => $t['description'],
                    ];
                }
            }
        }
        dd($translationsData);

        foreach ($translationsData as $t) {
            $page->translations()->create($t);
        }

        return response()->json([
            'message' => 'OK',
            // 'data' => $page->load('translations'),
        ], 201);
    }

    public function delete()
    {
        $pages = Page::with('translations')->find(request()->id);

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
