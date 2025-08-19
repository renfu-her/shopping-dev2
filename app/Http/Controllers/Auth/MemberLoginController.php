<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('member')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }

    public function profile()
    {
        $member = Auth::guard('member')->user();
        return view('pages.auth.profile', compact('member'));
    }

    public function updateProfile(Request $request)
    {
        $member = Auth::guard('member')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $member->update($request->only(['name', 'email', 'phone', 'address']));

        return back()->with('success', 'Profile updated successfully.');
    }
}
