<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $store = auth()->user()->stores()->first();
        if (!$store) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sizes' => 'nullable|string',
            'colors' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'max:3'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = '/storage/' . $path;
            }
        }

        $sizes = $request->filled('sizes') ? array_map('trim', explode(',', $request->sizes)) : [];
        $colors = $request->filled('colors') ? array_map('trim', explode(',', $request->colors)) : [];

        $product = Product::create([
            'store_id' => $store->id,
            'category_id' => $request->category_id,
            'brand' => $request->brand,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'sizes' => $sizes,
            'colors' => $colors,
            'images' => $imagePaths,
            'is_active' => true
        ]);

        return redirect('/dashboard/seller')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $store = auth()->user()->stores()->first();
        if ($product->store_id !== $store->id) abort(403);

        return view('product.edit', compact('product', 'store'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $store = auth()->user()->stores()->first();
        if ($product->store_id !== $store->id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sizes' => 'nullable|string',
            'colors' => 'nullable|string',
        ]);

        $sizes = $request->filled('sizes') ? array_map('trim', explode(',', $request->sizes)) : [];
        $colors = $request->filled('colors') ? array_map('trim', explode(',', $request->colors)) : [];

        $product->update([
            'category_id' => $request->category_id,
            'brand' => $request->brand,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'sizes' => $sizes,
            'colors' => $colors,
        ]);

        return redirect('/dashboard/seller')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $store = auth()->user()->stores()->first();
        if ($product->store_id !== $store->id) abort(403);

        $product->delete();
        return back()->with('success', 'Product deleted.');
    }
}
