@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 2rem auto; background: var(--bg-surface); padding: 2rem; border-radius: 16px; border: 1px solid var(--border);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Edit Product</h2>
        <a href="/dashboard/seller" style="color: var(--text-secondary); text-decoration: underline;">Back to Dashboard</a>
    </div>
    
    @if($errors->any())
        <div style="color: var(--danger); margin-bottom: 1rem;">
            @foreach($errors->all() as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('seller.products.update', $product->id) }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1rem;">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label style="color:var(--text-secondary); margin-bottom:0.5rem; display:block">Category</label>
                <select name="category_id" required style="width:100%; padding: 0.75rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary);">
                    @foreach(\App\Models\Category::where('is_active', true)->get() as $cat)
                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="color:var(--text-secondary); margin-bottom:0.5rem; display:block">Brand</label>
                <input type="text" name="brand" value="{{ $product->brand }}" style="width:100%; padding: 0.75rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary);" placeholder="e.g. Nike, Apple">
            </div>
        </div>

        <div>
            <label style="color:var(--text-secondary); margin-bottom:0.5rem; display:block">Product Name</label>
            <input type="text" name="name" value="{{ $product->name }}" required style="width:100%; padding: 0.75rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary);" placeholder="e.g. Wireless Headphones">
        </div>

        <div>
            <label style="color:var(--text-secondary); margin-bottom:0.5rem; display:block">Description</label>
            <textarea name="description" rows="4" style="width:100%; padding: 0.75rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary);" placeholder="Describe the product details...">{{ $product->description }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label style="color:var(--text-secondary); margin-bottom:0.5rem; display:block">Available Sizes (Comma separated)</label>
                <input type="text" name="sizes" value="{{ is_array($product->sizes) ? implode(', ', $product->sizes) : '' }}" style="width:100%; padding: 0.75rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary);" placeholder="S, M, L, XL or 7, 8, 9">
            </div>
            <div>
                <label style="color:var(--text-secondary); margin-bottom:0.5rem; display:block">Available Colors (Comma separated)</label>
                <input type="text" name="colors" value="{{ is_array($product->colors) ? implode(', ', $product->colors) : '' }}" style="width:100%; padding: 0.75rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary);" placeholder="Black, White, Blue">
            </div>
        </div>

        <div>
            <label style="color:var(--text-secondary); margin-bottom:0.5rem; display:block">Price (₹)</label>
            <input type="number" step="0.01" name="price" value="{{ $product->price }}" required style="width:100%; padding: 0.75rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary);" placeholder="0.00">
        </div>

        <div>
            <label style="color:var(--text-secondary); margin-bottom:0.5rem; display:block">Stock Quantity</label>
            <input type="number" name="stock" value="{{ $product->stock }}" required style="width:100%; padding: 0.75rem; border-radius:8px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary);" placeholder="0">
        </div>
        
        <button type="submit" class="btn" style="width:100%; margin-top: 1rem;">Update Product</button>
    </form>
</div>
@endsection
