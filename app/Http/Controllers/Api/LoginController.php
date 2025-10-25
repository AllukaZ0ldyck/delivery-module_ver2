<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->guard('admin')->attempt($credentials)) {

            $admin = auth()->guard('admin')->user();

            if ($admin->user_type !== 'delivery_man') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not authorized as Delivery Man',
                ], 401);
            }

            // Use PHP associative array syntax (=>) for abilities/metadata
            $token = $admin->createToken('authToken', ['role' => 'delivery_man'])->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'data' => $admin
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out',
        ], 200);
    }


}
