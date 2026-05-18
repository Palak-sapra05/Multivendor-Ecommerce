<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    public function showRegister()
    {
        return view('seller.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255|unique:stores,name',
            'description' => 'required|string',
        ]);

        $store = Store::create([
            'user_id' => auth()->id(),
            'name' => $request->store_name,
            'slug' => Str::slug($request->store_name) . '-' . time(),
            'description' => $request->description,
            'is_approved' => false,
            'status' => 'pending',
            'balance' => 0
        ]);

        // Update user role to seller (though approval is pending)
        auth()->user()->update(['role' => 'seller']);

        return redirect('/dashboard/seller')->with('success', 'Store application submitted! Please wait for admin approval.');
    }
}
