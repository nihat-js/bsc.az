<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpec;
use App\Services\ImageUploadService;
use DB;
use Illuminate\Http\Request;
use Storage;
use Str;

class ProductController extends Controller
{

    public function one($id)
    {
        $product = Product::with("translations")
            ->with("specs")
            ->with("images")
            ->findOrFail($id);

        // Group the specs by `spec_id` and map each group
        $arr = collect();

        foreach ($product->specs as $spec) {
            $t = $arr->first(function ($item) use ($spec) {
                return $item["spec_id"] == $spec->spec_id;
            });

            if ($t) {
                $arr = $arr->map(function ($item) use ($spec, $t) {
                    if ($item["spec_id"] == $t["spec_id"]) {
                        $item["data"][] = $spec;  // Add the spec to the existing data
                    }
                    return $item;
                });
            } else {
                $arr->push([
                    "spec_id" => $spec->spec_id,
                    "data" => [$spec]
                ]);
            }
        }
        $product = $product->toArray();
        $product["specs"] = $arr;



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
            "cover_image" => "nullable|string",

            "specs" => "nullable|array",
            "specs.*.spec_id" => "required|integer|exists:category_specs,id",
            "specs.*.data" => "required|array",
            "specs.*.data.*.text" => "required|string",
            "specs.*.data.*.option_id" => "nullable|integer|exists:category_spec_options,id",
            "specs.*.data.*.translations" => "nullable|array",
            "specs.*.data.*.translations.*.lang_code" => "required|string|exists:languages,code",
            "specs.*.data.*.translations.*.text" => "required|string",



            'translations' => 'sometimes|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code', // Validate lang_id
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',

            "images" => "nullable|array",
            "images.*.data" => "required|string",

            "campaign_id" => "nullable|integer|exists:campaigns,id",
            // "images.*." => "string",
        ]);

        DB::beginTransaction();
        $validated["slug"] = Str::slug($validated['name']);
        $folderName = uniqid('product_', true);  // A unique folder name for each product
        $uploadPath = 'uploads/products/' . $folderName;
        Storage::disk('public')->makeDirectory($uploadPath);

        if (@$validated["cover_image"]) {
            $coverImage = ImageUploadService::uploadBase64Image($validated["cover_image"], $uploadPath);
            $validated["cover_image"] = $folderName . "/" . $coverImage;
            // $product->cover_image = $coverImage;
            // $product->save();
        }

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
                // return response()->json($spec);
                $data = $spec["data"];
                foreach ($data as $d) {
                    $d["spec_id"] = $spec["spec_id"];
                    $s = $product->specs()->create($d);
                    if (@$d["translations"]) {
                        foreach ($d["translations"] as $translation) {
                            $translation["table_name"] = "product_specs";
                            $s->translations()->create($translation);
                        }
                    }
                }
            }
        }

        if (@$validated["colors"]) {
            $array = collect($validated["colors"])->map(function ($color) {
                return ["color_id" => $color];
            });
            $product->colors()->createMany($array);
        }


        if (@$validated["images"]) {
            $filenames = [];
            foreach ($validated["images"] as $image) {
                $filename = ImageUploadService::uploadBase64Image($image["data"], $uploadPath);
                $filenames[] = $folderName . "/" . $filename;
            }
            foreach ($filenames as $index => $filename) {
                $product->images()->create([
                    "path" => $filename,
                    "rank" => ++$index
                ]);
            }
        }

        if (@$validated["campaign_id"]) {
            $campaign = Campaign::find($validated["campaign_id"]);
            $campaign->products()->create([
                "product_id" => $product->id,
            ]);
        }

        DB::commit();

        return response()->json([
            "status" => "ok",
            'message' => 'Product created successfully',
            'data' => $product->load('translations')
            ->load("specs")
            ->load("images")
            ->load("colors")
            ->load("category")
        ], 201);
    }


    public function all(Request $request)
    {
        $page = (int) request()->query("p") ?: 1;
        $limit = (int) request()->query("l") ?: 50;

        $allCount = Product::count();

        // $filter = $request->filter;/

        $products = Product::with("translations")
            ->with("specs")
            ->with("images")
            ->with("category")
            ->skip(($page - 1) * $limit)->take($limit)->get();

        $arr = collect();

        $products = $products->toArray();
        foreach ($products as &$product) {
            foreach ($product["specs"] as $spec) {
                $t = $arr->first(function ($item) use ($spec) {
                    return $item["spec_id"] == $spec["spec_id"];
                });

                if ($t) {
                    $arr = $arr->map(function ($item) use ($spec, $t) {
                        if ($item["spec_id"] == $t["spec_id"]) {
                            $item["data"][] = $spec;  // Add the spec to the existing data
                        }
                        return $item;
                    });
                } else {
                    $arr->push([
                        "spec_id" => $spec["spec_id"],
                        "data" => [$spec]
                    ]);
                }
            }
            // $product = $product->toArray();
            $product["specs"] = $arr;
        }

        return response()->json([
            "message" => "OK", 
            "count" => $allCount,
            "data" => $products,
        ]);
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
            "country_id" => "nullable|integer|exists:countries,id",
            "brand_id" => "nullable|integer|exists:brands,id",
            "colors" => "nullable|array",
            "colors.*" => "integer|exists:color_catalog,id",
            "cover_image" => "nullable|string",

            "specs" => "nullable|array",
            "specs.*.spec_id" => "required|integer|exists:category_specs,id",
            "specs.*.data" => "required|array",
            "specs.*.data.*.text" => "required|string",
            "specs.*.data.*.option_id" => "nullable|integer|exists:category_spec_options,id",
            "specs.*.data.*.translations" => "nullable|array",
            "specs.*.data.*.translations.*.lang_code" => "required|string|exists:languages,code",
            "specs.*.data.*.translations.*.text" => "required|string",

            'translations' => 'sometimes|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code', // Validate lang_id
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',

            "images" => "nullable|array",
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
            }
        }

        if (@$validated["specs"]) {
            foreach ($validated["specs"] as $spec) {
                // return response()->json($spec);
                $data = $spec["data"];
                foreach ($data as $d) {
                    $d["spec_id"] = $spec["spec_id"];
                    $s = $product->specs()->updateOrCreate(
                        ["spec_id" => $spec["spec_id"]],
                        $d
                    );
                    if (@$d["translations"]) {
                        foreach ($d["translations"] as $translation) {
                            $translation["table_name"] = "product_specs";
                            $s->translations()->updateOrCreate(
                                ["lang_code" => $translation["lang_code"]],
                                $translation
                            );
                        }
                    }
                }
            }
        }

        if (@$validated["colors"]) {
            $c = collect($validated["colors"]);
            $product->colors()->whereNotIn("color_id", $c)->delete();
            foreach ($validated["colors"] as $color) {
                $product->colors()->updateOrCreate(
                    [
                        "color_id" => $color
                    ],
                    ["color_id" => $color]
                );
            }
        }

        DB::commit();
        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product->load('translations')
            ->load("specs")
            ->load("images")
            ->load("colors")
            ->load("category")
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
        $product->colors()->delete();
        $product->images()->delete();
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

    public function search(Request $request)
    {
        $q = $request->q;
        $products = Product::where('name', 'like', "%$q%")->get();
        return response()->json(["message" => "OK", "data" => $products]);
    }

    public function getProductByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get();
        return response()->json(["message" => "OK", "data" => $products]);
    }

    public function getProductByBrand($brandId)
    {
        $products = Product::where('brand_id', $brandId)->get();
        return response()->json(["message" => "OK", "data" => $products]);
    }

    public function deleteCoverImage()
    {
        $product = Product::find(request()->id);

        Storage::disk('public')->delete("products" . "/" . $product->cover_image);

        $product->update(["cover_image" => null]);
        return response()->json(["message" => "OK",]);
    }

    public function deleteImage($id, $imageId)
    {
        DB::beginTransaction();
        $image = ProductImage::where("product_id", $id)
            ->findOrFail($imageId);
        Storage::disk('public')->delete("products" . "/" . $image->path);
        $image->delete();
        DB::commit();
        return response()->json(["message" => "OK", "data" => $image]);
    }

}
