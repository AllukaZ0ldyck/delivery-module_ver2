<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Customer login
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            // Check the role of the logged-in user using Auth::user()
            if (Auth::user() && Auth::user()->role === 'delivery_personnel') {

                // Redirect to the delivery personnel dashboard
                return redirect()->route('delivery-personnel.index');
            }

            // For Customer login, default redirect
            return redirect()->route('account-overview.index');
        }

        // Admin login
        if (class_exists(\App\Models\Admin::class)) {
            if (Auth::guard('admins')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard'); // Redirect to admin dashboard
            }
        }

        // ---- FAILED LOGIN ----
        return back()
            ->withInput()
            ->withErrors(['email' => 'Email or password is incorrect']);
    }

    public function logout(Request $request)
    {
        // logout all guards
        Auth::guard('web')->logout();
        if (class_exists(\App\Models\Admin::class)) {
            Auth::guard('admins')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
