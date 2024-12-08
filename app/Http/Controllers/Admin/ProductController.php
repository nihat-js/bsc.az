<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $page = (int) request()->query("p") ?: 1;
        $limit = (int) request()->query("l") ?: 100;

        $filter = $request->filter;

                



        $products = Product::with("translations")->skip(($page - 1) * $limit)->take($limit)->get();

        return response()->json(["message" => "OK", "data" => $products]);
    }

    public function uploadImage(){
        $file = request()->file("file");
        $newFilename = time(). "_" . rand(11111,99999). "." . $file->getClientOriginalExtension();
        $file->storeAs("public/products", $file->getClientOriginalName());
        return response()->json(["message" => "OK", "data" => $file->getClientOriginalName()]);
    }

    public function arrangeImages(){
        $images = request()->images;
        foreach ($images as $image) {
            $image = ProductImage::find($image["id"]);
            $image->update(["order" => $image["order"]]);
        }
        return response()->json(["message" => "OK", "data" => $images]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'is_visible' => 'required|boolean',
            'add_basket' => 'required|boolean',
            'discount_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'file' => 'nullable|string',
            'translations' => 'required|array',
            'translations.*.lang_id' => 'required|integer|exists:languages,id', // Validate lang_id
            'translations.*.slug' => 'required|string|unique:product_translates,slug|max:255',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            "images" => "nullable|array",
            // "images.*." => "string",
        ]);

        // Create the product
        $product = Product::create([
            'is_visible' => $validated['is_visible'],
            'add_basket' => $validated['add_basket'],
            'discount_price' => $validated['discount_price'],
            'price' => $validated['price'],
            'file' => $validated['file'],
        ]);
     
        // ["imdage1" : "order:1,","imag2","image3"]

        if ($validated["images"]) {
            // $
            $product->images()->update(["product_id" => $product->id]);
            $product->images()->create([
                "path" => $images['path'],
                "order" => $images['order'] ?? 0, // Default to 0 if not provided
            ]);

            
            // $imagesData = [

            
            // foreach ($validated["images"] as $image) {
            //     $product->images()->create(["file" => $image]);
            //     // save image
            // }
            // $is_main =
            // $is_visible
            // $image
        }

        // Add translations
        foreach ($validated['translations'] as $translation) {
            $product->translations()->create($translation);
        }

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product->load('translations'), // Include translations in the response
        ], 201);
    }


    public function details()
    {
        $product = Product::with("translations")->find(request()->id);
        return response()->json(["message" => "OK", "data" => $product]);
    }


    public function edit(Request $request, $id)
    {
        // Find the product
        $product = Product::with('translations')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Validate the request
        $validated = $request->validate([
            'is_visible' => 'sometimes|boolean',
            'add_basket' => 'sometimes|boolean',
            'discount_price' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'file' => 'nullable|string',
            'translations' => 'sometimes|array',
            // 'translations.*.id' => 'nullable|integer|exists:product_translates,id',
        ]);
        // dd($validated);

        $product->update([
            'is_visible' => $validated['is_visible'] ?? $product->is_visible,
            'add_basket' => $validated['add_basket'] ?? $product->add_basket,
            'discount_price' => $validated['discount_price'] ?? $product->discount_price,
            'price' => $validated['price'] ?? $product->price,
            'file' => $validated['file'] ?? $product->file,
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
        $product = Product::with('translations')->find(request()->id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        foreach ($product->translations as $translation) {
            $translation->delete();
        }
        $product->delete();

        return response()->json([
            'message' => 'Product and its translations deleted successfully',
            'data' => $product
        ], 200);
    }

}
