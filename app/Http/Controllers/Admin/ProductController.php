<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageUploadService;
use DB;
use Illuminate\Http\Request;
use Storage;
use Str;

class ProductController extends Controller
{

    public function one()
    {
        $product = Product::with("translations")
            ->with("specs")
            ->findOrFail(request()->id);
        return response()->json(["message" => "ok", "data" => $product]);
    }
    public function add(Request $request)
    {
        $validated = $request->validate([
            "category_id" => "required|integer|exists:categories,id",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            'price' => 'required|numeric|min:0',
            "discount_price" => "nullable|numeric|min:0",
            "is_visible" => "sometimes|boolean",
            "weight" => "nullable|string",
            "dimension" => "nullable|string",
            "country_id" => "nullable|integer|exists:countries,id",
            "brand_id" => "nullable|integer|exists:brands,id",
            "colors" => "nullable|array",
            "colors.*" => "integer|exists:color_catalog,id",

            'translations' => 'sometimes|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code', // Validate lang_id
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',

            "images" => "nullable|array",
            "specs" => "nullable|array",
            // "images.*." => "string",
        ]);

        DB::beginTransaction();
        $validated["slug"] = Str::slug($validated['name']);

        // return response()->json($validated);
        $product = Product::create($validated);
        if (@$validated["translations"]) {
            foreach ($validated['translations'] as $translation) {
                $translation["slug"] = Str::slug($translation['name']);
                $product->translations()->create($translation);
            }
        }

        if (@$validated["specs"]) {
            foreach ($validated["specs"] as $spec) {
                $product->specs()->create($spec);
            }
        }
        if (@$validated["colors"]) {
            $array = collect($validated["colors"])->map(function ($color) {
                return ["color_id" => $color];
            });
            $product->colors()->createMany($array);
        }




        if (@$validated["images"]) {
            $folderName = uniqid('product_', true);  // A unique folder name for each product
            $uploadPath = 'uploads/products/' . $folderName;
            Storage::disk('public')->makeDirectory($uploadPath);

            $filenames = [];
            foreach ($validated["images"] as $image) {
                ImageUploadService::uploadBase64Image($image, "products");
            }

            //     foreach ($validated["images"] as $index => $image) {
            //         // Generate a random filename with timestamp
            //         $filename = time() . '_' . str_random(10) . '.' . $image->getClientOriginalExtension();

            //         // Store the image with the new filename
            //         $image->storeAs("public/products", $filename);

            //         // Save image details in the database
            //         $product->images()->create([
            //             "path" => "products/" . $filename, // Store relative path in the database
            //             "order" => ++$index,
            //         ]);
            //     }
        }


        DB::commit();

        return response()->json([
            'message' => 'Product created successfully',
            // 'data' => $product->load('translations'), // Include translations in the response
        ], 201);
    }

    public function all(Request $request)
    {
        $page = (int) request()->query("p") ?: 1;
        $limit = (int) request()->query("l") ?: 100;

        $filter = $request->filter;

        $products = Product::with("translations")
            ->with("specs")
            ->skip(($page - 1) * $limit)->take($limit)->get();

        return response()->json(["message" => "OK", "data" => $products]);
    }

    public function uploadImage()
    {
        $file = request()->file("file");
        $newFilename = time() . "_" . rand(11111, 99999) . "." . $file->getClientOriginalExtension();
        $file->storeAs("public/products", $file->getClientOriginalName());
        return response()->json(["message" => "OK", "data" => $file->getClientOriginalName()]);
    }

    public function arrangeImages()
    {
        $images = request()->images;
        foreach ($images as $image) {
            $image = ProductImage::find($image["id"]);
            $image->update(["order" => $image["order"]]);
        }
        return response()->json(["message" => "OK", "data" => $images]);
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
        DB::beginTransaction();
        $validated = $request->validate([
            "category_id" => "sometimes|integer|exists:categories,id",
            "name" => "sometimes|string|max:255",
            "description" => "nullable|string",
            'price' => 'sometimes|numeric|min:0',
            "discount_price" => "nullable|numeric|min:0",
            "is_visible" => "sometimes|boolean",
            "weight" => "nullable|string",
            "dimension" => "nullable|string",
            "country_id" => "nullable|integer",
            "brand_id" => "nullable|integer",

            'translations' => 'sometimes|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code', // Validate lang_id
            'translations.*.name' => 'required|string|max:255',
            "translations.*.slug" => "required|string",
            'translations.*.description' => 'nullable|string',

            "images" => "nullable|array",
            "specs" => "nullable|array",
            "specs.*.spec_id" => "required|integer|exists:specs,id",
            "specs.*.text" => "required|string",
            // "images.*." => "string",
        ]);

        // dd($validated);

        $product->update($validated);


        if (@$validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                // if (!$translation->slug){
                //     $translation->slug = Str::slug($translation['name']);
                // }
                $product->translations()->updateOrCreate(
                    ["lang_code" => $translation["lang_code"]],
                    $translation
                );
                //     if (isset($translationData['lang_id'])) {
                //         $translation = $product->translations()->where("lang_id", "=", $translationData['lang_id'])->first();
                //         if ($translation) {
                //             $translation->update($translationData);
                //         } else {
                //             // dd($translationData);
                //             $product->translations()->create($translationData);
                //         }
                //     }
                // }
            }
        }

        if (@$validated["specs"]) {
            foreach ($validated["specs"] as $spec) {
                $product->specs()->updateOrCreate(
                    ["spec_id" => $spec["spec_id"]],
                    $spec
                );
            }
        }

        DB::commit();
        return response()->json([
            'message' => 'Product updated successfully',
            // 'data' => $product->load('translations'),
        ], 200);
    }



    public function delete(Request $request)
    {

        // return response()->json([
        //     'status' => 'ok',
        //     'message' => 'Product deleted successfully',
        //     'data' => "roduct"
        // ], 200);


        $product = Product::findOrFail($request->id);
        DB::beginTransaction();
        $product->translations()->delete();
        $product->specs()->delete();
        $product->delete();
        // $product->images()->delete();

        DB::commit();

        return response()->json([
            'status' => 'ok',
            'message' => 'Product and its translations deleted successfully',
            // 'data' => $product
        ], 200);
    }

}
