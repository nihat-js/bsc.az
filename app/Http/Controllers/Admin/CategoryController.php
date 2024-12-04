<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function all()
    {
        $categories = Category::with("translations")->get();
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
            'is_visible' => 'required|boolean',
            "url" => "required|string",
            // 'type' => 'required|integer',
            // 'has_url' => 'required|boolean',
            // 'redirect_url' => 'nullable|string',
            'translations' => 'nullable|array',
            'translations.*.lang_id' => 'required|exists:languages,id',
            'translations.*.slug' => 'required|string|unique:category_translates,slug',
            'translations.*.name' => 'required|string',
        ]);

        // dd($validated);

        $category = Category::create($validated);
        if (@$validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                $category->translations()->create(
                    [
                        'lang_id' => $translation['lang_id'],
                        'name' => $translation['name'],
                        'slug' => $translation['slug'],
                    ]
                );
            }
        }

        return response()->json(["status" => "ok", "data" => $category->load('translations')], 201);
    }

    public function one(Request $request, int $id)
    {
        $category = Category::with("translations")->findOrFail($id);
        return response()->json(["status" => "ok", "data" => $category], 200);
    }

    public function edit(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'is_visible' => 'sometimes|boolean',
            'url' => 'sometimes|string',
            // 'type' => 'sometimes|integer',
            'translations' => 'nullable|array',
            'translations.*.lang_id' => 'required|exists:languages,id',
            'translations.*.slug' => 'required|string|',
            'translations.*.name' => 'required|string',
        ]);

        $category = Category::find($request->id);
        // return response()->json($validated);
        // dd($validated);
        $category->update($validated);
        if (@$validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                $category->translations()->updateOrCreate(
                    ['lang_id' => $translation['lang_id']],
                    [
                        'name' => $translation['name'],
                        'slug' => $translation['slug'],
                    ]
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
        return response()->json(["status" => "ok"]);
    }
}
