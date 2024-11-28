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
            'code' => 'required|string|unique:languages,code|max:10',
            'name' => 'required|string|max:255',
        ]);

        $language = Language::create($validated);

        return response()->json($language, 201);
    }

    public function details($id)
    {
        $language = Language::find($id);
        if (!$language) {
            return response()->json(['message' => 'Language not found'], 404);
        }
        return response()->json($language, 200);
    }

    public function update(Request $request, $id)
    {
        $language = Language::find($id);

        if (!$language) {
            return response()->json(['message' => 'Language not found'], 404);
        }

        $validated = $request->validate([
            'is_visible' => 'sometimes|boolean',
            'code' => 'sometimes|string|unique:languages,code,' . $id . '|max:10',
            'name' => 'sometimes|string|max:255',
        ]);

        $language->update($validated);

        return response()->json($language, 200);
    }

    public function delete($id)
    {
        $language = Language::find($id);

        if (!$language) {
            return response()->json(['message' => 'Language not found'], 404);
        }

        $language->delete();

        return response()->json(['message' => 'Language deleted successfully'], 200);
    }
}
