<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use DB;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:countries|max:255',
            'code' => 'required|unique:countries|max:255',
            'is_visible' => 'sometimes|boolean',
            'phone_code' => 'nullable|max:255',
            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string|max:255|exists:languages,code",
            "translations.*.text" => "required|string",
        ]);


        DB::beginTransaction();
        $country = Country::create($validated);

        if (@$validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                $country->translations()->create(
                    [
                        "table_name" => "countries",
                        "lang_code" => $translation["lang_code"],
                        "text" => $translation["text"],
                    ]
                );
            }
        }




        DB::commit();

        return response()->json([
            'error' => false,
            'message' => 'Country added successfully',
            'data' => $country
        ]);

    }


    public function edit(Request $request, $id)
    {


        $validated = $request->validate([
            'name' => 'nullable|max:255',
            'code' => 'nullable|max:255|',
            'is_visible' => 'sometimes|boolean',
            'phone_code' => 'nullable|max:255',
            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string|max:255|exists:languages,code",
            "translations.*.text" => "required|string",
        ]);

        DB::beginTransaction();
        $country = Country::findOrFail($id);
        $country->update($validated);

        if (@$validated['translations']) {
            foreach ($validated['translations'] as $translation) {
                $country->translations()->updateOrCreate(
                    [
                        "table_name" => "countries",
                        "lang_code" => $translation["lang_code"],
                    ],
                    [
                        "text" => $translation["text"],
                    ]
                );
            }
        }

        DB::commit();

        return response()->json(['message' => 'Country updated successfully']);
    }


    public function all()
    {
        $countries = Country::with('translations')->get();
        return response()->json(["status" => "ok", "data" => $countries]);
    }

    public function one($id){
        $country = Country::with('translations')->findOrFail($id);
        return response()->json(["status" => "ok", "data" => $country]);
    }

    public function delete($id)
    {
        $country = Country:: with("translations")->findOrFail($id);

        $country->translations()->delete();
        $country->delete();
        return response()->json(['message' => 'Country deleted successfully']);
    }
}
