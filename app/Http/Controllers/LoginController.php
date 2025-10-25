<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;

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

        // ✅ 1. CUSTOMER LOGIN
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

            if ($user->approval_status !== 'approved') {
                Auth::guard('web')->logout();
                return redirect()->route('auth.index')
                    ->with('pending', 'Your account is still pending admin approval.');
            }

            return redirect()->route('account-overview.index');
        }

        // ✅ 2. ADMIN / DELIVERY / staff LOGIN
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            $admin = Auth::guard('admin')->user();

            switch ($admin->user_type) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'delivery':
                    return redirect()->route('delivery-personnel.index');
                case 'staff':
                    return redirect()->route('staff.index');
                default:
                    Auth::guard('admin')->logout();
                    return redirect()->route('auth.index')->withErrors([
                        'email' => 'Unauthorized access level.',
                    ]);
            }
        }

        // ❌ Invalid credentials
        return back()->withErrors([
            'email' => 'Email or password is incorrect.',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        // Logout both guards
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
