<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function all()
    {
        $partners = Partner::all();

        return response()->json(["message" => "OK", "data" => $partners]);
    }

    public function details(Partner $partner)
    {
        return response()->json(["message" => "OK", "data" => $partner]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'is_visible' => 'required|boolean',
            'logo' => 'required|string',
            'file' => 'required|file|mimes:jpeg,png,jpg,|max:10240',
        ]);

        $partner = Partner::create($request->all());

        return response()->json(["message" => "OK", "data" => $partner], 201); 
    }

    public function edit(Request $request, Partner $partner)
    {
        $request->validate([
            'is_visible' => 'required|boolean',
            'logo' => 'required|string',
            'file' => 'required|string',  // Assuming 'file' is a string or path
        ]);

        $partner->update($request->all());

        return response()->json($partner);
    }

    public function delete(Partner $partner)
    {
        $partner->delete();

        return response()->json(['message' => 'Partner deleted successfully.']);
    }
}
