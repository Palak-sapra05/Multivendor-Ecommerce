@extends('layouts.app')

@section('content')
<style>
    .auth-wrapper {
        display: flex;
        min-height: calc(100vh - 150px);
        background: var(--bg-surface);
        border-radius: 24px;
        overflow: hidden;
        margin: 2rem auto;
        max-width: 1100px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--border);
    }

    .auth-side-image {
        flex: 1.2;
        position: relative;
        overflow: hidden;
        display: block;
    }

    .auth-side-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .auth-side-image .overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 60%, transparent 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 3rem;
        color: white;
    }

    .auth-side-image h1 {
        font-size: 3rem;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 1rem;
        letter-spacing: -1px;
    }

    .auth-form-container {
        flex: 1;
        padding: 3rem 4rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: var(--bg-surface);
        overflow-y: auto;
    }

    .register-header {
        margin-bottom: 2rem;
    }

    .register-header h2 {
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .role-switcher {
        display: flex;
        background: var(--bg-base);
        border-radius: 12px;
        padding: 4px;
        margin-bottom: 2rem;
        border: 1px solid var(--border);
    }

    .role-btn {
        flex: 1;
        text-align: center;
        padding: 0.75rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 700;
        transition: all 0.3s ease;
        color: var(--text-secondary);
    }

    .role-btn.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 0.4rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-control {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: var(--bg-base);
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: var(--danger);
        background-color: rgba(239, 68, 68, 0.05);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 0.4rem;
        display: flex;
        align-items: center;
    }

    .google-btn:hover {
        transform: translateY(-2px);
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
    }

    .divider::before, .divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .divider span {
        padding: 0 1rem;
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 700;
    }

    @media (max-width: 900px) {
        .auth-side-image {
            display: none;
        }
        .auth-wrapper {
            max-width: 500px;
            margin: 2rem auto;
        }
        .auth-form-container {
            padding: 3rem 2rem;
        }
    }
</style>

<div class="auth-wrapper">
    <div class="auth-side-image">
        <img src="/images/auth-hero.png" alt="Fashion Background">
        <div class="overlay">
            <h1>Join the <br>NexMart Era</h1>
            <p style="opacity: 0.8; font-size: 1.1rem;">Discover curated collections and exceptional value. Your journey to smart shopping starts here.</p>
        </div>
    </div>
    
    <div class="auth-form-container">
        <div class="register-header">
            <h2>{{ request('role') === 'seller' ? 'Create Seller Account' : 'Get Started' }}</h2>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">Join thousands of smart shoppers today.</p>
        </div>

        <div class="role-switcher">
            <a href="/register?role=customer" class="role-btn {{ request('role') !== 'seller' ? 'active' : '' }}">
                Buyer
            </a>
            <a href="/register?role=seller" class="role-btn {{ request('role') === 'seller' ? 'active' : '' }}">
                Seller
            </a>
        </div>

        @if($errors->any())
            <div style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem; border: 1px solid rgba(239, 68, 68, 0.2);">
                @foreach($errors->all() as $err)
                    <p style="margin: 0;">{{ $err }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <input type="hidden" name="role" value="{{ request('role') === 'seller' ? 'seller' : 'customer' }}">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="John Doe" value="{{ old('name') }}" required>
                @error('name')
                    <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="john@example.com" value="{{ old('email') }}" required>
                @error('email')
                    <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                    @error('password')
                        <span style="color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Confirm</label>
                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn" style="width:100%; padding: 0.85rem; font-size: 0.95rem;">Create Account</button>
        </form>

        <div class="divider">
            <span>OR</span>
        </div>

        <a href="/auth/google" class="google-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20px" height="20px" style="margin-right: 0.75rem;">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            Sign up with Google
        </a>

        <div style="text-align:center; margin-top: 2rem;">
            <p style="font-size: 0.9rem; color: var(--text-secondary);">Already have an account? <a href="/login" style="color: var(--primary); text-decoration: none; font-weight: 700;">Sign in here</a></p>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('svg');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }
</script>
@endsection

