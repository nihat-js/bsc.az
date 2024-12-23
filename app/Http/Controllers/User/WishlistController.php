<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    public function all(Request $request)
    {

        $w = Wishlist::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'status' => 'ok',
            'data' => $w
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required'
        ]);

        $validated['user_id'] = auth()->user()->id;

        $isExists = Wishlist::where('user_id', auth()->user()->id)->where('product_id', $validated['product_id'])->first();
        if ($isExists){
            return response()->json([
                'status' => 'error',
                'message' => 'Product already exists in wishlist'
            ]);
        }
        Wishlist::create($validated);
        $w2 = Wishlist::where('user_id', auth()->user()->id)->get();
        return response()->json([
            'status' => 'ok',
            'data' => $w2,
        ]);
    }

    public function clear(Request $request)
    {
        Wishlist::where('user_id', auth()->user()->id)->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Wishlist cleared'
        ]);
    }

    public function remove(Request $request,$productId){

        $isExists = Wishlist::where('user_id', auth()->user()->id)->where('product_id', $productId)->first();

        if (!$isExists){
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found in wishlist'
            ]);
        }

        Wishlist::where('user_id', auth()->user()->id)->where('product_id', $productId)->delete();

        $w = Wishlist::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'Product removed from wishlist',
            'data' => $w
        ]);
    }
}
