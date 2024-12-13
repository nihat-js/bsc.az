<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dictionary;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    public function add(Request $request)
    {
        $validated = $request->validate([
            'word' => 'required|string|unique:dictionary,word',
            'meaning' => 'required|string',
            "translations" => "nullable|array"
        ]);

        $dictionary = Dictionary::create($validated);

        if (@$request["translations"]) {
            $dictionary->translations()->createMany($request["translations"]);
        }

        return response()->json([
            "status" => "ok",
            "message" => "Word added successfully"
        ]);

    }

    public function byLanguage(Request $request,$lang_code){
        $validated = $request->validate([
            'language' => 'required|string'
        ]);

        // if ($lang_code == "az"){
        //     $dictionary = Dictionary::l`
        // }

        $dictionary = Dictionary::with("translations")->whereHas("translations", function($query) use ($validated){
            $query->where("language", $validated["language"]);
        })->get();

        return response()->json([
            "status" => "ok",
            "data" => $dictionary
        ]);
    }

    public function all(Request $request)
    {
        $dictionary = Dictionary::with("translations")->get();

        return response()->json([
            "status" => "ok",
            "data" => $dictionary
        ]);
    }

    public function delete($id){

        $dictionary = Dictionary::findOrFail($id);
        $dictionary->delete();

        return response()->json([
            "status" => "ok",
            "message" => "Deleted successfully"
        ]);
    }
}
