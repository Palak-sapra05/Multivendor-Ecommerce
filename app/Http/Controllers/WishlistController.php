<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = session()->get('wishlist', []);
        $products = Product::whereIn('id', $wishlist)->get();
        
        return view('wishlist.index', compact('products'));
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $wishlist = session()->get('wishlist', []);

        if (!in_array($productId, $wishlist)) {
            $wishlist[] = $productId;
            session()->put('wishlist', $wishlist);
        }

        return back()->with('success', 'Product added to wishlist!');
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $wishlist = session()->get('wishlist', []);

        if (($key = array_search($productId, $wishlist)) !== false) {
            unset($wishlist[$key]);
            session()->put('wishlist', $wishlist);
        }

        return back()->with('success', 'Product removed from wishlist!');
    }
}
