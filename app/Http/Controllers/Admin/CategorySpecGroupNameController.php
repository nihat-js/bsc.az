<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorySpecGroupName;
use App\Models\Translation;
use App\Services\ImageUploadService;
use DB;
use Illuminate\Http\Request;
use Storage;

class CategorySpecGroupNameController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:category_spec_group_names,name,NULL,id,category_id,' . $request->category_id,
            'image' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'translations' => 'nullable|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string',
        ]);



        $path = "uploads/category_spec_group_names";
        Storage::disk("public")->makeDirectory($path);
        if ($request->has("image")) {
            $image = ImageUploadService::uploadBase64Image($request->image, $path);
            $request->merge(["image" => $image]);
        }


        DB::beginTransaction();

        $categorySpecGroupName = CategorySpecGroupName::create($request->all());

        // return ["a" => $categorySpecGroupName->id];

        if ($request->has("translations")) {

            foreach ($request->translations as $translation) {
                Translation::create([
                    "table_id" => $categorySpecGroupName->id,
                    "table_name" => "category_spec_group_names",
                    "lang_code" => $translation['lang_code'],
                    "text" => $translation['name']
                ]);
            }

        }
        DB::commit();


        return response()->json([
            "error" => false,
            "status" => "ok",
            "message" => "Category Spec Group Name created",
            "data" => $categorySpecGroupName->load("translations")
        ]);
    }

    public function all()
    {
        $categorySpecGroupNames = CategorySpecGroupName::with("translations")
            ->with("category")
            ->get();

        return response()->json($categorySpecGroupNames);
    }

    public function one($id)
    {
        $categorySpecGroupName = CategorySpecGroupName::with("translations")
            ->with("category")
            ->findOrFail($id);

        return response()->json($categorySpecGroupName);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'translations' => 'nullable|array',
            'translations.*.lang_code' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string',
        ]);

        $categorySpecGroupName = CategorySpecGroupName::findOrFail($id);
        if ($request->has("image")) {
            $path = "uploads/category_spec_group_names";
            if ($categorySpecGroupName->image) {
                Storage::disk("public")->delete("uploads/category_spec_group_names/" . $categorySpecGroupName->getRawOriginal("image"));
            }
            $image = ImageUploadService::uploadBase64Image($request->image, $path);
            $request->merge(["image" => $image]);
        }
        $categorySpecGroupName->update($request->all());

        if ($request->has("translations")) {
            foreach ($request->translations as $translation) {
                // if translation exists, update it
                $result = Translation::where("table_id", $id)->where("table_name", "category_spec_group_names")->update(["text" => $translation["name"]]);
                if (!$result) {
                    Translation::create([
                        "table_id" => $id,
                        "table_name" => "category_spec_group_names",
                        "lang_code" => $translation['lang_code'],
                        "text" => $translation['name']
                    ]);
                }
            }
        }

        return response()->json([
            "status" => "ok",
            "message" => "Category Spec Group Name updated",
            "data" => $categorySpecGroupName->load("translations")
                ->load("category")
        ]);
    }

    public function delete($id)
    {
        $categorySpecGroupName = CategorySpecGroupName::findOrFail($id);

        DB::beginTransaction();
        if ($categorySpecGroupName->image) {
            Storage::disk("public")->delete("uploads/category_spec_group_names/" . $categorySpecGroupName->getRawOriginal("image"));
        }

        $categorySpecGroupName->translations()->delete();
        $categorySpecGroupName->delete();

        DB::commit();

        return response()->json([
            "status" => "ok",
            "message" => "Category Spec Group Name deleted",
        ]);
    }

    public function getByCategory($id)
    {
        $categorySpecGroupNames = CategorySpecGroupName::where("category_id", $id)
            ->with("translations")
            ->with("category")
            ->get();
        return response()->json([
            "status" => "ok",
            "data" => $categorySpecGroupNames
        ]);
    }
}
