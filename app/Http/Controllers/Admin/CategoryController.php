<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function add()
    {

    }
    public function all()
    {
        $categories = Category::with("trnslations")->get();
        return response()->json($categories);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'is_visible' => 'required|boolean',
            'type' => 'required|integer',
            'has_url' => 'required|boolean',
            'redirect_url' => 'nullable|string',
            'translations' => 'required|array',
            'translations.*.lang_id' => 'required|exists:languages,id',
            'translations.*.slug' => 'required|string|unique:category_translates,slug',
            'translations.*.name' => 'required|string',
        ]);

        $category = Category::create($validated);

        foreach ($validated['translations'] as $translation) {
            $category->translations()->create($translation);
        }

        return response()->json($category->load('translations'), 201);
    }
}
