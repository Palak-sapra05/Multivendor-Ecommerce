<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'The email address you entered is not registered.'])->withInput($request->only('email'));
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The password you entered is incorrect. Please try again.'])->withInput($request->only('email'));
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            $role = Auth::user()->role;
            if ($role === 'admin') return redirect()->intended('/dashboard/admin');
            if ($role === 'seller') return redirect()->intended('/dashboard/seller');
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:customer,seller'
        ], [
            'password.confirmed' => 'Your passwords do not match. Please make sure they are exactly the same.',
            'email.unique' => 'This email address is already registered.',
            'password.min' => 'Password must be at least 6 characters long.'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'seller') {
            Store::create([
                'user_id' => $user->id,
                'name' => $request->name . "'s Store",
                'slug' => \Str::slug($request->name . ' store ' . uniqid()),
            ]);
        }

        Auth::login($user);
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
