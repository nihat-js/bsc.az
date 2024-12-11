<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use DB;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function add(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|unique:brands,name',
            "is_visible" => "sometimes|boolean",
            'image' => 'nullable|image',
        ]);

        DB::beginTransaction();
        $brand = Brand::create($validated);

        DB::commit();
        return response()->json([
            "status" => "ok",
            'message' => 'Brand added successfully',
            "data" => $brand
        ]);
    }

    public function all(){
        $brands = Brand::all();
        return response()->json([
            "status" => "ok",
            "data" => $brands
        ]);
    }

    public function one($id){

        $brand = Brand::findOrFail($id);
        return response()->json([
            "status" => "ok",
            "data" => $brand
        ]);
    }

    public function delete($id){
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return response()->json([
            "status" => "ok",
            "message" => "Brand deleted successfully"
        ]);
    }

    public function edit($id){
        $brand = Brand::findOrFail($id);
        $validated = request()->validate([
            'name' => 'sometimes|string|unique:brands,name,'.$brand->id,
            "is_visible" => "sometimes|boolean",
            'image' => 'nullable|image',
        ]);

        DB::beginTransaction();
        $brand->update($validated);
        DB::commit();
        return response()->json([
            "status" => "ok",
            'message' => 'Brand updated successfully',
            "data" => $brand
        ]);
    }
}
