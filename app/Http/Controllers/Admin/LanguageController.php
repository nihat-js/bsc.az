<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use Illuminate\Http\Request;




class LanguageController 
{
    /**
     * Display a listing of the languages.
     */
    public function index()
    {
        $languages = Language::all();
        return response()->json($languages, 200);
    }

    /**
     * Store a newly created language in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'is_visible' => 'required|boolean',
            'code' => 'required|string|unique:languages,code|max:10',
            'name' => 'required|string|max:255',
        ]);

        $language = Language::create($validated);

        return response()->json($language, 201);
    }

    public function show($id)
    {
        $language = Language::find($id);

        // if (!$language) {
        //     return response()->json(['message' => 'Language not found'], 404);
        // }

        return response()->json($language, 200);
    }

    /**
     * Update the specified language in storage.
     */
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

    /**
     * Remove the specified language from storage.
     */
    public function destroy($id)
    {
        $language = Language::find($id);

        if (!$language) {
            return response()->json(['message' => 'Language not found'], 404);
        }

        $language->delete();

        return response()->json(['message' => 'Language deleted successfully'], 200);
    }
}
