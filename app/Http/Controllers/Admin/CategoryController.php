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
    public function add(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'is_visible' => 'required|boolean',
            'type' => 'required|integer',
            'has_url' => 'required|boolean',
            'redirect_url' => 'nullable|string',
            'translations' => 'nullable|array',
            'translations.*.lang_id' => 'required|exists:languages,id',
            'translations.*.slug' => 'required|string|unique:category_translates,slug',
            'translations.*.name' => 'required|string',
        ]);

        // dd($validated);

        $category = Category::create($validated);
        if ($validated['translations']) {
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

        return response()->json($category->load('translations'), 201);
    }

    public function details(Request $request, int $id){
        $category = Category::with("translations")->find($id);

        return response()->json($category);
    }

    public function edit(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'is_visible' => 'required|boolean',
            'type' => 'required|integer',
            'has_url' => 'required|boolean',
            'redirect_url' => 'nullable|string',
            'translations' => 'nullable|array',
            'translations.*.lang_id' => 'required|exists:languages,id',
            'translations.*.slug' => 'required|string|',
            'translations.*.name' => 'required|string',
        ]);

        $category = Category::find($request->id);
        $category->update($validated);
        if ($validated['translations']) {
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

    public function delete(){
        $category = Category::find(request()->id);
        $category->translations()->delete();
        $category->delete();
        return response()->json(["message" => "OK"]);
    }
}
