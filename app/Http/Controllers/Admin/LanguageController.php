<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use Illuminate\Http\Request;




class LanguageController
{
    public function all()
    {
        $languages = Language::all();
        return response()->json($languages, 200);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'is_visible' => 'required|boolean',
            'code' => 'required|string|unique:languages,code|max:2',
            'name' => 'required|string|max:255',
        ]);

        $language = Language::create($validated);

        return response()->json(["status" => "ok", "data" => "lang", $language,], 201);
    }

    public function one($id)
    {
        $language = Language::findOrFail($id);

        return response()->json(["status" => "ok", "data" => $language], 200);
    }

    public function oneByKey($key)
    {
        $language = Language::where("key", $key)->first();

        return response()->json(["status" => "ok", "data" => $language], 200);
    }

    public function edit(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $validated = $request->validate([
            'is_visible' => 'sometimes|boolean',
            'code' => 'sometimes|string|unique:languages,code,' . $id . '|max:2',
            'name' => 'sometimes|string|max:255',
        ]);

        $language->update($validated);

        return response()->json(["status" => "ok", "data" => $language], 200);
    }

    public function delete($id)
    {
        $language = Language::findOrFail($id);
        $language->delete();

        return response()->json(['status' => 'ok'], 200);
    }
}
