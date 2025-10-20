<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class QrLoginController extends Controller
{
    public function loginViaQr($token)
    {
        $user = User::where('qr_token', $token)->first();

        if (!$user) {
            return redirect('/login')->with('error', 'Invalid or expired QR code.');
        }

        // ✅ Log in the user automatically
        Auth::login($user);

        // ✅ Redirect to the "Place Order" page
        return redirect()->route('orders.create')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }
}
