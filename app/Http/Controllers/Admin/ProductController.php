<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all()
    {
        $page = (int) request()->query("p") ?: 1;
        $limit = (int) request()->query("l") ?: 10;
        
        $products = Product::skip(($page-1) * $limit)->take($limit)->get();
        // dd($page);

        return response()->json(["message" => "OK", "data" => $products]);
    }

    public function details(){
        $product = Product::find(request()->id);
        return response()->json(["message" => "OK", "data" => $product]);
    }

    public function add(){
        $product = new Product();
        $product->name = request()->name;
        $product->price = request()->price;
        $product->save();
        return response()->json(["message" => "OK", "data" => $product]);
    }

    public function edit(){
        $product = Product::find(request()->id);
        $product->name = request()->name;
        $product->price = request()->price;
        $product->save();
        return response()->json(["message" => "OK", "data" => $product]);
    }

    public function delete(){
        $product = Product::find(request()->id);
        $product->delete();
        return response()->json(["message" => "OK", "data" => $product]);
    }
}
