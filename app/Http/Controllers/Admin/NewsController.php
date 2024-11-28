<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsTranslate;
use Illuminate\Http\Request;


class NewsController extends Controller
{
    public function all()
    {
        $page = (int) request()->query("p") ?: 1;
        $limit = (int) request()->query("l") ?: 10;

        $products = News::with("translations")->skip(($page - 1) * $limit)->take($limit)->get();

        return response()->json(["message" => "OK", "data" => $products]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'is_visible' => 'required|boolean',
            'image' => 'nullable|string',

            'translations' => 'nullable|array',
            'translations.*.lang_id' => 'required|integer|exists:languages,id', // Validate lang_id
            'translations.*.slug' => 'required|string|unique:product_translates,slug|max:255',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        $news = News::create([
            'is_visible' => $validated['is_visible'],
            'image' => $validated['image'],
        ]);

        if ($validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                $news->translations()->create($translation);
            }
        }

        return response()->json([
            'message' => 'OK',
            'data' => $news->load('translations'),  
        ], 201);
    }

    public function getBySlug($slug)
    {
        $newsTranslate = NewsTranslate::where('slug', $slug)->first();
        $news = News::with("translations")->find($newsTranslate->id);


        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }
        return response()->json($news);
    }


    public function details()
    {
        $product = News::with("translations")->find(request()->id);
        if (!$product) {
            return response()->json(['message' => 'NOT_FOUND'], 404);
        }
        return response()->json(["message" => "OK", "data" => $product]);
    }


    public function edit(Request $request, $id)
    {
        // Find the product
        $product = News::with('translations')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Validate the request
        $validated = $request->validate([
            'is_visible' => 'sometimes|boolean',
            "image" => "nullable|string",

            'translations' => 'sometimes|array',
            // 'translations.*.id' => 'nullable|integer|exists:product_translates,id',
        ]);
        // dd($validated);

        $product->update([
            'is_visible' => $validated['is_visible'] ?? $product->is_visible,
            'image' => $validated['image'] ?? $product->image,
        ]);

        if ($validated['translations']) {
            foreach ($validated['translations'] as $translationData) {
                if (isset($translationData['lang_id'])) {
                    $translation = $product->translations()->where("lang_id", "=", $translationData['lang_id'])->first();
                    if ($translation) {
                        $translation->update($translationData);
                    } else {
                        // dd($translationData);
                        $product->translations()->create($translationData);
                    }
                }
            }
        }
        return response()->json([
            'message' => 'Product updated successfully',
            // 'data' => $product->load('translations'),
        ], 200);
    }



    public function delete()
    {
        $product = News::with('translations')->find(request()->id);

        if (!$product) {
            return response()->json(['message' => 'NOT_FOUND'], 404);
        }
        foreach ($product->translations as $translation) {
            $translation->delete();
        }
        $product->delete();

        return response()->json([
            'message' => 'OK',
            // 'data' => $product
        ], 200);
    }

}