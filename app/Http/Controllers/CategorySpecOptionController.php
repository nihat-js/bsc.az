<?php

namespace App\Http\Controllers;

use App\Models\Admin\CategorySpecOption;
use App\Models\CategorySpecs;
use DB;
use Illuminate\Http\Request;

class CategorySpecOptionController extends Controller
{
    public function add(Request $request)
    {

        $validated = $request->validate(
            [
                'category_spec_id' => 'required|integer|exists:category_specs,id',
                'text' => 'required|string',
            ]
        );

        DB::beginTransaction();
        $categorySpecOption = CategorySpecOption::create($validated);

        if (@$validated["translations"]) {
            foreach ($validated["translations"] as $translation) {
                $categorySpecOption->translations()->create($translation);
            }
        }
        DB::commit();


        return response()->json([
            'status' => ' ok',
            'message' => 'Category spec option added successfully'
        ]);
    }

    public function edit(Request $request,$id)
    {
        $validated = $request->validate(
            [
                'text' => 'required|string',
                "translations" => "nullable|array",
                "translations.*.lang_code" => "required|exists:languages,code",
                "translations.*.text" => "required|string",

            ]
        );

        $categorySpecOption = CategorySpecOption::with("translations")->findOrFail($id);
        DB::beginTransaction();
        $categorySpecOption->update($validated);


        if (@$validated["translations"]) {
            foreach ($validated["translations"] as $translation) {
                $translation["table_name"] = "category_spec_options";
                $categorySpecOption->translations()->updateOrCreate(
                    [
                        "lang_code" => $translation['lang_code']
                    ],
                    $translation
                );
            }
        }

        DB::commit();



        return response()->json([
            'status' => ' ok',
            'message' => 'Category spec option updated successfully',
            'data' => $categorySpecOption->refresh()
        ]);
    }


    public function bulkAdd(Request $request)
    {
        $validated = $request->validate([
            "category_spec_id" => "required|integer|exists:category_specs,id",
            "data" => "required|array",
        ]);

        DB::beginTransaction();

        foreach ($validated["data"] as $data) {
            $data["category_spec_id"] = $validated["category_spec_id"];
            $categorySpecOption = CategorySpecOption::create($data);

            if (@$data["translations"]) {
                foreach ($data["translations"] as $translation) {
                    $translation["table_name"] = "category_spec_options";
                    $categorySpecOption->translations()->create($translation);
                }
            }
        }

        DB::commit();
        return response()->json([
            'status' => ' ok',
            'message' => 'Category spec option bulk added successfully'
        ]);

    }

    public function all()
    {
        $categorySpecOptions = CategorySpecOption::with("translations")->get();
        return response()->json([
            'status' => ' ok',
            'data' => $categorySpecOptions
        ]);
    }
    public function categoryOptions($categoryId)
    {
        $categorySpecs = CategorySpecs::where('category_id', $categoryId)->get();
        if (!$categorySpecs->count()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No category spec found for this category'
            ], 404);
        }
        $categorySpecOptions = CategorySpecOption::whereIn('category_spec_id', $categorySpecs->pluck('id'))->get();

        return response()->json([
            'status' => ' ok',
            'data' => $categorySpecOptions
        ]);
    }

    public function delete($id)
    {


        $categorySpecOption = CategorySpecOption::findOrFail($id);
        DB::beginTransaction();
        $categorySpecOption->translations()->delete();
        $categorySpecOption->delete();
        DB::commit();
        return response()->json([
            'status' => ' ok',
            'message' => 'Category spec option deleted successfully'
        ]);
    }

}
