<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Display a list of all products
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // Display a single product
    public function show(Product $product)
    {
        // Return the product as a JSON response
        return response()->json($product);
    }

    // Store a newly created product in the database
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'is_visible' => 'required|boolean',
            'add_basket' => 'required|boolean',
            'discount_price' => 'required|numeric',
            'price' => 'required|numeric',
            'file' => 'required|string',  // Adjust file validation if needed
        ]);

        // Create the product using the validated data
        $product = Product::create($request->all());

        // Return the created product as a JSON response with a success message
        return response()->json($product, 201);  // 201 is the HTTP status code for "Created"
    }

    // Update the specified product in the database
    public function update(Request $request, Product $product)
    {
        // Validate the incoming request data
        $request->validate([
            'is_visible' => 'required|boolean',
            'add_basket' => 'required|boolean',
            'discount_price' => 'required|numeric',
            'price' => 'required|numeric',
            'file' => 'required|string',  // Adjust file validation if needed
        ]);

        // Update the product with the validated data
        $product->update($request->all());

        // Return the updated product as a JSON response
        return response()->json($product);
    }

    // Delete the specified product from the database
    public function destroy(Product $product)
    {
        // Delete the product
        $product->delete();

        // Return a success message as JSON
        return response()->json(['message' => 'Product deleted successfully.']);
    }
}
