<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorySpecs;
use DB;
use Illuminate\Http\Request;

class CategorySpecsController extends Controller
{

    public function add(Request $request)
    {

        DB::beginTransaction();
        $request->validate([
            "category_id" => "required|exists:categories,id",
            "name" => "string|required", // default olaraq az dilde olmalidir diglerini translationla elave edeceyik
            "group_id" => "nullable|integer",
            "show_in_filter" => "nullable|boolean",
            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string|exists:languages,code",
        ]);


        $categorySpecs = CategorySpecs::create([
            "category_id" => $request->category_id,
            "name" => $request->name,
            "group_id" => $request->group_id,
            "show_in_filter" => $request->show_in_filter,
        ]);

        if ($request->translations) {
            foreach ($request->translations as $index => $translation) {
                $categorySpecs->translations()->create([
                    "table_name" => "category_specs",
                    "lang_code" => $translation["lang_code"],
                    "text" => $translation["text"],
                ]);
            }
        }

        DB::commit();

        return response()->json([
            "status" => "ok",
            "message" => "Category Specs created"
        ]);
    }

    public function all()
    {
        $categorySpecs = CategorySpecs::with("category.translations")->get();

        foreach ($categorySpecs as $categorySpec) {
            $translations = [];
            foreach ($categorySpec->category->translations as $translation) {
                $translations[$translation->lang] = $translation->name;
            }
            $categorySpec->category->translations = $translations;
        }
        $translations = [];

        return response()->json([
            "status" => "ok",
            "data" => $categorySpecs
        ]);
    }

    public function one($categoryId)
    {
        $categorySpecs = CategorySpecs::find($categoryId)->with("category.translations")->get();
        return response()->json([
            "status" => "ok",
            "data" => $categorySpecs
        ]);
    }

    

    public function delete()
    {

    }

    public function edit(Request $request, $id)
    {

        $validated = $request->validate([
            "category_id" => "sometimes|exists:categories,id",
            "name" => "sometimes|string",
            "group_id" => "nullable|integer",
            "show_in_filter" => "nullable|boolean",
        ]);

        $categorySpecs = CategorySpecs::find($id);
        $categorySpecs->update($validated);

        return response()->json([
            "status" => "ok",
            "data" => $categorySpecs
        ]);
    }
}
