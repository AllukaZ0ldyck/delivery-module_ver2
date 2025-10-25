<?php


namespace App\Http\Controllers;

use App\Services\ProfileService;
use App\Services\PropertyTypesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public $profileService;
    public $propertyTypesService;

    public function __construct(ProfileService $profileService, PropertyTypesService $propertyTypesService) {
        $this->profileService = $profileService;
        $this->propertyTypesService = $propertyTypesService;
    }

    // Show the profile of the authenticated user or any specific user
    public function index() {
        // Get the authenticated user
        $user = auth()->user();

        // If the user is not a customer, return access denied
        if ($user->user_type !== 'client') {
            return redirect()->route('home')->with('error', 'Access Denied');
        }

        // Fetch property types (if needed)
        $property_types = $this->propertyTypesService::getData();

        // Get the last order if it exists
        $lastOrder = $user->orders()->latest()->first();
        $lastOrderDays = $lastOrder ? Carbon::parse($lastOrder->created_at)->diffInDays(Carbon::now()) : null;

        // Pass all the data to the view
        return view('profile.index', [
            'user' => $user, // Pass the user
            'property_types' => $property_types,  // Pass property types if needed
            'lastOrder' => $lastOrder, // Pass the last order
            'lastOrderDays' => $lastOrderDays // Pass the number of days ago
        ]);
    }



    // Update profile of the current user
    public function update(Request $request)
    {
        $user = Auth::user();
        $id = $user->id;
        $user_type = $user->user_type ?? 'customer'; // or role

        $payload = $request->all();

        // ✅ Updated Validation Rules
        $validator = Validator::make($payload, [
            'name'          => 'required|string|max:255',
            'email'         => ['required', 'email', Rule::unique('users')->ignore($id)],
            'contact'       => 'required|string|max:20',
            'address'       => 'required|string|max:255',
            'gallon_type'   => 'required|string|max:50',
            'gallon_count'  => 'required|integer|min:1',
            'password'      => 'nullable|min:8|required_with:confirm_password',
            'confirm_password' => 'nullable|same:password|required_with:password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ Handle password hashing (optional)
        if (!empty($payload['password'])) {
            $payload['password'] = bcrypt($payload['password']);
        } else {
            unset($payload['password']);
        }

        // ✅ Update using ProfileService
        $response = $this->profileService::update($id, $payload);

        // ✅ Return success/error message
        if ($response['status'] === 'success') {
            return redirect()->back()->with('alert', [
                'status' => 'success',
                'message' => 'Profile updated successfully!',
            ]);
        } else {
            return redirect()->back()->withInput()->with('alert', [
                'status' => 'error',
                'message' => $response['message'],
            ]);
        }
    }

}
