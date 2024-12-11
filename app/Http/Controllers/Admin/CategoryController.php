<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use DB;
use Illuminate\Http\Request;
use Str;

class CategoryController extends Controller
{
    public function all()
    {
        // dd("e");
        $categories = Category::with("translations")
            ->where("parent_id", null)
            ->get();
        return response()->json($categories);
    }

    public function getChild()
    {
        $categories = Category::where("parent_id", request()->id)
            ->with("translations")
            ->get()
            ->map(function ($category) {
                // Check if the category has children
                $category->is_parent = Category::where("parent_id", $category->id)->exists();
                return $category;
            });

        return response()->json($categories);
    }
    public function add(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'is_visible' => 'boolean',
            "name" => "required|string|unique:categories,name",
            // "slug" => "required|string|unique:categories,slug",// avtomatik
            'translations' => 'nullable|array',
            'translations.*.lang_code' => 'required|exists:languages,code',
            'translations.*.name' => 'required|string',
            // 'translations.*.slug' => 'required|string|unique:category_translations,slug',
        ]);
        // dd($validated);

        $validated["slug"] = Str::slug($validated["name"]);
        
        DB::beginTransaction();
        $category = Category::create($validated);
        if (@$validated["translations"]) {
            foreach ($validated['translations'] as $translation) {
                $category->translations()->create(
                    [
                        'lang_code' => $translation['lang_code'],
                        'name' => $translation['name'],
                        "slug" => Str::slug($translation['name'])
                        // 'slug' => $translation['slug'],

                    ]
                );
            }
        }
        DB::commit();

        return response()->json(["status" => "ok", "data" => $category->load('translations')], 201);
    }

    public function one(Request $request, int $id)
    {
        // return response()->json($id);
        $category = Category::with("translations")->findOrFail($id);
        return response()->json(["status" => "ok", "data" => $category], 200);
    }

    public function edit(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'is_visible' => 'nullable|boolean',
            "name" => "nullable|string",
            "slug" => "nullable|string",// avtomatik
            'translations' => 'nullable|array',
            'translations.*.lang_code' => 'required|exists:languages,code',
            'translations.*.name' => 'nullable|string',
            'translations.*.slug' => 'nullable|string',
        ]);

        // return response()->json($validated);
        $category = Category::findOrFail($request->id);
        // dd($validated);
        $category->update($validated);
        if (@$validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                $category->translations()->updateOrCreate(
                    ['lang_code' => $translation['lang_code']],
                    $translation
                );
            }
        }

        return response()->json($category->load('translations'), 201);
    }

    public function delete()
    {
        $category = Category::findOrFail(request()->id);
        $category->translations()->delete();
        $category->delete();
        return response()->json(["status" => "ok","message"=>"Category deleted"], 200);
    }
}
