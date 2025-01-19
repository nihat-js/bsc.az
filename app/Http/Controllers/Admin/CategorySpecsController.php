<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CategorySpecOption;
use App\Models\CategorySpecs;
use App\Models\Translation;
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
            "group_name_id" => "nullable|exists:category_spec_group_names,id", // qruplari da yaratmaq olar
            "show_in_filter" => "nullable|boolean",
            "options" => "nullable|array",
            "options.*.text" => "required|string",
            "options.*.translations" => "nullable|array",
            "options.*.translations.*.lang_code" => "required|string|exists:languages,code",
            "options.*.translations.*.text" => "required|string",
            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string|exists:languages,code",
            "translations.*.text" => "required|string",
        ]);


        $categorySpecs = CategorySpecs::create($request->all());

        if ($request->translations) {
            foreach ($request->translations as $index => $translation) {
                $categorySpecs->translations()->create([
                    "table_name" => "category_specs",
                    "lang_code" => $translation["lang_code"],
                    "text" => $translation["text"],
                ]);
            }
        }

        if ($request->options) {
            foreach ($request->options as $o) {
                $categorySpecOption = CategorySpecOption::create([
                    "category_spec_id" => $categorySpecs->id,
                    "text" => $o["text"],
                ]);
                if (@$o["translations"]) {
                    foreach ($o["translations"] as $translation) {
                        $translation["table_name"] = "category_spec_options";
                        $categorySpecOption->translations()->create($translation);
                    }
                }
            }
        }


        DB::commit();

        return response()->json([
            "status" => "ok",
            "message" => "Category Specs created",
            "data" => $categorySpecs->load("translations")->load("options")->load("options.translations")
            ->load("group_name")->load("group_name.translations")
        ]);
    }

    public function all()
    {

        $categorySpecs = CategorySpecs::with("options", "options.translations", "category", "translations","group_name","group_name.translations")
            ->get();

        // foreach ($categorySpecs as $categorySpec) {
        //     $translations = [];
        //     foreach ($categorySpec->category->translations as $translation) {
        //         $translations[$translation->lang] = $translation->name;
        //     }
        //     $categorySpec->category->translations = $translations;
        // }
        // $translations = [];

        return response()->json([
            "status" => "ok",
            "data" => $categorySpecs
        ]);
    }

    public function one($id)
    {
        // $categorySpecs = CategorySpecs::find($id)
        //     ->leftJoin("categories", "category_specs.category_id", "=", "categories.id")
        //     ->select("category_specs.*", "categories.name as category_name", "categories.slug as category_slug")
        //     ->first();

        // $categorySpecs = $categorySpecs->toArray();
        // $translations = Translation::where("table_name", "category_specs")->where("table_id", $id)->get();

        $categorySpecs = CategorySpecs::with("translations")
            ->with("options")
            ->with("options.translations")
            ->findOrFail($id);


        return response()->json([
            "status" => "ok",
            "data" => $categorySpecs
        ]);
    }

    public function getByCategory($id)
    {
        $categorySpecs = CategorySpecs::where("category_id", $id)
            ->with("translations")
            ->with("options")
            ->get();

        return response()->json([
            "status" => "ok",
            "data" => $categorySpecs
        ]);
    }



    public function delete($id)
    {
        $categorySpecs = CategorySpecs::findOrFail($id);

        $categorySpecs->options()->delete();
        $categorySpecs->translations()->delete();
        $categorySpecs->delete();

        return response()->json([
            "status" => "ok",
            "message" => "Category Specs deleted"
        ]);
    }

    public function edit(Request $request, $id)
    {

        $validated = $request->validate([
            "category_id" => "sometimes|exists:categories,id",
            "name" => "sometimes|required", // default olaraq az dilde olmalidir diglerini translationla elave edeceyik
            "group_name" => "sometimes|string", // 
            "show_in_filter" => "nullable|boolean",
            // "options.*.id" => "required|exists:category_spec_options,id",
            "options.*.text" => "required|string",
            "options.*.translations" => "nullable|array",
            "options.*.translations.*.lang_code" => "required|string|exists:languages,code",
            "options.*.translations.*.text" => "required|string",
            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string|exists:languages,code",
            "translations.*.text" => "required|string",
        ]);




        DB::beginTransaction();
        $categorySpecs = CategorySpecs::findOrFail($id);
        $categorySpecs->update($validated);

        if ($request->has('options')) {
            $categorySpecOptionsTexts = $categorySpecs->options()->pluck("text")->toArray();
            $optionTexts = array_column($request->options, "text");
            $optionTextsToDelete = array_diff($categorySpecOptionsTexts, $optionTexts);
            if ($optionTextsToDelete) {
                CategorySpecOption::where("category_spec_id", $categorySpecs->id)
                    ->whereIn("text", $optionTextsToDelete)->delete();
            }
        }

        foreach ($request->options as $option) {
            $categorySpecOption = CategorySpecOption::where("category_spec_id", $categorySpecs->id)
                ->where("text", $option["text"])->first();
            if ($categorySpecOption) {
                $categorySpecOption->update($option);
            } else {
                $categorySpecOption = CategorySpecOption::create(
                    [
                        "category_spec_id" => $categorySpecs->id,
                        "text" => $option["text"],
                    ]
                );
            }
            if (@$option["translations"]) {
                foreach ($option["translations"] as $translation) {
                    $translation["table_name"] = "category_spec_options";
                    $categorySpecOption->translations()->updateOrCreate(
                        [
                            "table_name" => "category_spec_options",
                            "table_id" => $categorySpecOption->id,
                            "lang_code" => $translation["lang_code"],
                        ],
                        $translation
                    );
                }
            }
        }

        // return ["okaaa",$categorySpecs];


        DB::commit();
        // $categorySpecs = $categorySpecs->with("category");

        // $data = CategorySpecs::leftJoin("categories", "category_specs.category_id", "=", "categories.id")
        //     ->with("translations")
        //     ->get();
        // ->leftJoin("translations","category_specs.id","=","translations.table_id")
        // ->where("translations.table_name","category_specs")
        // ->where("category_specs.id", $id)
        // ->select("category_specs.*", "categories.name as category_name")

        return response()->json([
            "status" => "ok",
            "data" => $categorySpecs->load("translations")->load("options")->load("options.translations")
        ]);
    }
}
