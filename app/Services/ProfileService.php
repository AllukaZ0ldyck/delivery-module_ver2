<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileService {

    // Get the data of the current authenticated user or any user by ID
    public static function getData($id = null) {
        // If $id is provided, return that user's data
        if ($id) {
            return User::find($id); // Assuming only users are being queried
        }

        // If no ID is provided, fetch the authenticated user's data
        $data = Auth::user();

        if ($data && isset($data->user_type)) {
            $data->user_type = $data->user_type;
        } else {
            $data->user_type = 'concesionnaire';
        }

        return $data;
    }

    // Update user or admin data
    public static function update(int $id, array $payload) {
        DB::beginTransaction();

        try {
            // Prepare data to be updated
            $updateData = [
                'name' => $payload['name'],
                'email' => $payload['email'],
            ];

            // Update password if provided
            if (isset($payload['password']) && $payload['password']) {
                $updateData['password'] = Hash::make($payload['password']);
            }

            // Update user or admin based on the user type
            if ($payload['user_type'] === 'client') {
                User::where('id', $id)->update($updateData);  // Update User model if 'client'
            } else {
                Admin::where('id', $id)->update($updateData);  // Update Admin model if 'admin'
            }

            // Commit the transaction
            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Profile updated successfully.'
            ];

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback if there's an error

            return [
                'status' => 'error',
                'message' => 'Error occurred: ' . $e->getMessage()
            ];
        }
    }
}
