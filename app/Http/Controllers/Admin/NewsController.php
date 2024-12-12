<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsTranslate;
use App\Models\NewsTranslation;
use DB;
use Illuminate\Http\Request;
use Str;


class NewsController extends Controller
{
    public function all()
    {
        $page = (int) request()->query("p") ?: 1;
        $limit = (int) request()->query("l") ?: 20;

        $products = News::with("translations")->skip(($page - 1) * $limit)->take($limit)->get();

        return response()->json(["message" => "OK", "data" => $products]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string",
            // "slug"  => avtomatik yaranacaq
            "description" => "nullable|string",
            'cover_image' => 'nullable|string',
            'is_visible' => 'required|boolean',

            'translations' => 'nullable|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code', 
            // 'translations.*.slug' => 'required|string|unique:product_translates,slug|max:255',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        DB::beginTransaction();
        $news = News::create($validated);

        if (@$validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                $translation["slug"] = Str::slug($translation['name']);
                $news->translations()->create($translation);
            }
        }

        DB::commit();
        return response()->json([
            'status' => 'ok',
            'data' => $news
        ], 201);
    }

    public function edit(Request $request, $id)
    {
        $product = News::with('translations')->findOrFail($id);

        $validated = $request->validate([
            "name" => "sometimes|string",
            "slug"  => "sometimes|string",
            "description" => "nullable|string",
            'cover_image' => 'nullable|string',
            'is_visible' => 'sometimes|boolean',

            'translations' => 'nullable|array',
            'translations.*.lang_code' => 'sometimes|string|exists:languages,code', 
            'translations.*.slug' => 'sometimes|string',
            'translations.*.name' => 'sometimes|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);
        // dd($validated);

        DB::beginTransaction();
        $product->update($validated);

        if (@$validated["translations"]){
            foreach ($validated["translations"] as $translation) {
                $translation["slug"] = Str::slug($translation['name']);
                $product->translations()->updateOrCreate(
                    ['lang_code' => $translation['lang_code']],
                    $translation
                );
            }
        }
        DB::commit();
        return response()->json([
            'status' => 'ok',
            'message' => 'Product updated successfully',
            // 'data' => $product->load('translations'),
        ], 200);
    }

    public function getBySlug($slug)
    {
        $newsTranslate = NewsTranslation::where('slug', $slug)->first();
        $news = News::with("translations")->findOrFail($newsTranslate->id);

        return response()->json($news);
    }


    public function one()
    {
        $product = News::with("translations")->findOrFail(request()->id);
        return response()->json(["message" => "OK", "data" => $product]);
    }



    public function delete($id)
    {
        $news = News::with('translations')->findOrFail($id);
        $news->translations()->delete();
        $news->delete();

        return response()->json([
            'status' => 'OK',
            'data' => $news
        ], 200);
    }

}