@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 4rem auto; background: var(--bg-surface); padding: 3rem; border-radius: 24px; border: 1px solid var(--border); box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 1rem;">Become a Seller</h2>
        <p style="color: var(--text-secondary); font-size: 1.1rem;">Join the NexMart community and start selling your products today.</p>
    </div>

    @if($errors->any())
        <div style="color: var(--danger); margin-bottom: 1.5rem; background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 12px;">
            @foreach($errors->all() as $err)
                <p style="margin: 0;">{{ $err }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('seller.register.submit') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf
        <div>
            <label style="color:var(--text-secondary); margin-bottom:0.75rem; display:block; font-weight: 600;">Store Name</label>
            <input type="text" name="store_name" required style="width:100%; padding: 1rem; border-radius:12px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary); font-size: 1rem;" placeholder="e.g. My Premium Boutique">
        </div>
        <div>
            <label style="color:var(--text-secondary); margin-bottom:0.75rem; display:block; font-weight: 600;">Store Description</label>
            <textarea name="description" rows="5" required style="width:100%; padding: 1rem; border-radius:12px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary); font-size: 1rem; resize: none;" placeholder="Tell us about what you sell..."></textarea>
        </div>
        
        <div style="background: rgba(37, 99, 235, 0.05); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(37, 99, 235, 0.1); margin-top: 1rem;">
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0;">By clicking register, you agree to our <span style="color: var(--primary); font-weight: 700;">Seller Terms and Conditions</span>. Your store will be active after admin approval.</p>
        </div>

        <button type="submit" class="btn" style="width:100%; padding: 1.25rem; font-size: 1.1rem; font-weight: 700; margin-top: 1rem; background: var(--primary); border-radius: 12px;">
            Submit Application
        </button>
    </form>
</div>
@endsection
