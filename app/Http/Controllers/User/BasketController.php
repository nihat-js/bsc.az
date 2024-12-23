<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\Product;
use Illuminate\Http\Request;

class BasketController extends Controller
{

    public function all()
    {
        $basket = Basket::where('user_id', auth()->id())->get();
        return response()->json([
            'message' => 'Basket fetched successfully',
            'status' => 'ok',
            'data' => $basket
        ]);
    }
    public function add(Request $request)
    {

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer'
        ]);
        $product = Product::findOrFail($request->product_id);

        $isExists = Basket::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();

        if ($isExists) {
            return response()->json([
                'message' => 'Product already exists in basket',
                'status' => 'error'
            ]);
        } else {
            $validated['user_id'] = auth()->id();
            $basket = Basket::create($validated);
        }

        $basket2 = Basket::where('user_id', auth()->id())->get();

        return response()->json([
            'message' => 'Product added to basket successfully',
            'status' => 'ok',
            'data' => $basket2
        ]);

    }

    public function edit(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1,max:9999'
        ]);

        $basket = Basket::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();


        if (!$basket) {
            return response()->json([
                'message' => 'Product not found in basket',
                'status' => 'error'
            ]);
        }

        if ($basket) {
            $basket->update([
                'quantity' => $request->quantity
            ]);
        }

        $basket2 = Basket::where('user_id', auth()->id())->get();
        return response()->json([
            'message' => 'Basket updated successfully',
            'status' => 'ok',
            'data' => $basket2
        ]);

    }

    public function total()
    {
        $basket = Basket::where('user_id', auth()->id())->get();
        $count = 0;
        $total = 0;
        foreach ($basket as $item) {
            $total += $item->product->price * $item->quantity;
            $count += $item->quantity;
        }
        return response()->json([
            'message' => 'Total fetched successfully',
            'status' => 'ok',
            'data' => [
                'total' => $total,
                'count' => $count
            ]
        ]);
    }





    public function clear(Request $request)
    {

        Basket::where('user_id', auth()->id())
            ->delete();
        return response()->json([
            'message' => 'Basket cleared successfully',
            'status' => 'ok'
        ]);
    }

    public function remove(Request $request,$productId)
    {
        

        $basket = Basket::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();

        if (!$basket) {
            return response()->json([
                'message' => 'Product not found in basket',
                'status' => 'error'
            ]);
        }

        $basket->delete();
        $basket2 = Basket::where('user_id', auth()->id())->get();
        return response()->json([
            'message' => 'Product removed from basket successfully',
            'status' => 'ok',
            'data' => $basket2
        ]);
    }
}
