<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;

class AdminProfileController extends Controller
{
    /**
     * Show the logged-in admin profile.
     */
    public function show($role)
    {
        $admin = Auth::guard('admin')->user();
         // Prevent access mismatch (e.g., /admin/profile by a Delivery user)
        if (strtolower($admin->user_type) !== strtolower($role)) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.profile.show', compact('admin', 'role'));
    }

    /**
     * Show edit profile form.
     */
    public function edit($role)
    {
        $admin = Auth::guard('admin')->user();
        if (strtolower($admin->user_type) !== strtolower($role)) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.profile.edit', compact('admin', 'role'));
    }

    /**
     * Handle profile updates.
     */
    public function update(Request $request, $role)
    {

        // dd($request->all());    
        $admin = Auth::guard('admin')->user();

        if (strtolower($admin->user_type) !== strtolower(string: $role)) {
            abort(403, 'Unauthorized access.');
        }

        // dd($request->all());
        // dd($admin->user_type, $role);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|min:6|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // dd('Validation passed');

        // dd($request->all());

        // ✅ Upload new profile picture
        if ($request->hasFile('profile_picture')) {
            if ($admin->profile_picture && Storage::exists('public/' . $admin->profile_picture)) {
                Storage::delete('public/' . $admin->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $admin->profile_picture = $path;
            // dd($path);
        } 

        // ✅ Update fields
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->address = $request->address;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('role.profile.show', ['role' => $role])
                         ->with('success', 'Profile updated successfully.');
    }
}
