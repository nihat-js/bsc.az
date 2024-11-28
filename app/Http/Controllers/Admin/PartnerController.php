<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::all();

        return response()->json($partners);
    }

    public function show(Partner $partner)
    {
        return response()->json($partner);
    }

    public function store(Request $request)
    {
        $request->validate([
            'is_visible' => 'required|boolean',
            'logo' => 'required|string',
            'file' => 'required|string',  // Assuming 'file' is a string or path, adjust validation if necessary
        ]);

        $partner = Partner::create($request->all());

        return response()->json($partner, 201); // 201 is the HTTP status code for "Created"
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'is_visible' => 'required|boolean',
            'logo' => 'required|string',
            'file' => 'required|string',  // Assuming 'file' is a string or path
        ]);

        $partner->update($request->all());

        return response()->json($partner);
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return response()->json(['message' => 'Partner deleted successfully.']);
    }
}
