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
    public static function update(int $id, array $payload)
    {
        DB::beginTransaction();

        try {
            // Prepare the base fields for update
            $updateData = [
                'name'   => $payload['name'] ?? null,
                'email'  => $payload['email'] ?? null,
            ];

            // ✅ Include all optional fields if they exist
            if (isset($payload['contact'])) {
                $updateData['contact'] = $payload['contact'];
            }

            if (isset($payload['address'])) {
                $updateData['address'] = $payload['address'];
            }

            if (isset($payload['gallon_type'])) {
                $updateData['gallon_type'] = $payload['gallon_type'];
            }

            if (isset($payload['gallon_count'])) {
                $updateData['gallon_count'] = $payload['gallon_count'];
            }

            // ✅ Update password only if provided
            if (!empty($payload['password'])) {
                $updateData['password'] = Hash::make($payload['password']);
            }

            // ✅ Determine user model type
            $userType = $payload['user_type'] ?? 'client'; // default to client if not set

            if ($userType === 'client' || $userType === 'customer') {
                User::where('id', $id)->update($updateData);
            } else {
                Admin::where('id', $id)->update($updateData);
            }

            DB::commit();

            return [
                'status'  => 'success',
                'message' => 'Profile updated successfully.',
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status'  => 'error',
                'message' => 'Error occurred: ' . $e->getMessage(),
            ];
        }
    }

}
