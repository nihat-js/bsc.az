<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ColorCatalog;
use DB;
use Illuminate\Http\Request;

class ColorCatalogController extends Controller
{
    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:color_catalog,name',
            'hex' => 'nullable|string',
            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string",
            "translations.*.text" => "required|string",
        ]);

        DB::beginTransaction();

        $color = ColorCatalog::create($validated);

        if (@$validated["translations"]) {
            foreach ($validated["translations"] as $translation) {
                $translation["table_name"] = "color_catalog";
                $color->translations()->create($translation);
            }
        }

        DB::commit();

        return response()->json([
            "status" => "ok",
            'message' => 'Color added successfully',
            'color' => $color
        ]);

    }

    public function edit(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'hex' => 'nullable|string',
            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string",
            "translations.*.text" => "required|string",
        ]);


        DB::beginTransaction();

        $color = ColorCatalog::with("translations")->findOrFail($id);
        $color->update($validated);

        if (@$validated["translations"]) {
            foreach ($validated["translations"] as $translation) {
                $translation["table_name"] = "color_catalog";
                $color->translations()->updateOrCreate(
                    [
                        "lang_code" => $translation["lang_code"],
                    ],
                    $translation
                )
                ;
            }
        }
        

        DB::commit();

        return response()->json([
            "status" => "ok",
            'message' => 'Color updated successfully',
            'data' => $color->with("translations")->get()
        ]);

    }

    public function all(){
        $colors = ColorCatalog::with("translations")->get();
        return response()->json([
            "status" => "ok",
            "colors" => $colors
        ]);
    }

    public function one($id){
        $color = ColorCatalog::with("translations")->findOrFail($id);

        return response()->json([
            "status" => "ok",
            "color" => $color
        ]);
    }


    public function delete($id){

        $color = ColorCatalog::findOrFail($id);
        $color->delete();

        return response()->json([
            "status" => "ok",
            "message" => "Color deleted successfully"
        ]);
    }
}
