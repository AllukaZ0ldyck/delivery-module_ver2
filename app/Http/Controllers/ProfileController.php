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
    public function update(string $user_type, int $id, Request $request) {
        $payload = $request->all();

        // Fetch user data based on ID (if needed for updating)
        $data = $this->profileService::getData($id);

        if ($data->user_type == 'customer') {
            $payload['user_type'] = 'customer';
        } else {
            $payload['user_type'] = 'admin';
        }

        // Validation logic
        $validator = Validator::make($payload, [
            'name' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($id),
            ],
            'password' => 'nullable|min:8|required_with:confirm_password',
            'confirm_password' => 'nullable|same:password|required_with:password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update user profile using the ProfileService
        $response = $this->profileService::update($id, $payload);

        // Success or error response
        if ($response['status'] === 'success') {
            return redirect()->back()->with('alert', [
                'status' => 'success',
                'message' => $response['message']
            ]);
        } else {
            return redirect()->back()->withInput()->with('alert', [
                'status' => 'error',
                'message' => $response['message']
            ]);
        }
    }
}
