<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Show list of all customers
    public function index()
    {
        try {
            // Always start with a safe empty collection
            $customers = collect();

            // ✅ Fetch only approved customers
            $customers = \App\Models\User::where('role', 'customer')
                ->where('approval_status', 'approved')
                ->with('orders') // eager load orders relationship
                ->orderBy('name', 'asc')
                ->get();

        } catch (\Throwable $e) {
            \Log::error('Error loading customers list: '.$e->getMessage());
            $customers = collect(); // fallback
        }

        return view('admin.customers.index', compact('customers'));
    }



    // Show the details of a single customer
    public function show($id)
    {
        // Find the customer by ID
        $customer = \App\Models\User::with(['orders', 'borrowedGallons'])->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    // Show the form to edit a customer
    public function edit($id)
    {
        // Find the customer by ID
        $customer = User::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    // Update the customer's information
    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        // ✅ Validate incoming data
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $id,
            'password'        => 'nullable|min:6|confirmed',
            'contact_no'      => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
            'gallon_type'     => 'nullable|string|max:255',
            'gallon_count'    => 'nullable|integer|min:0',
            'approval_status' => 'nullable|in:pending,approved,rejected',
            'status'          => 'nullable|in:active,archived',
        ]);

        // ✅ Update main info
        $customer->name            = $request->input('name');
        $customer->email           = $request->input('email');
        $customer->contact_no      = $request->input('contact_no');
        $customer->address         = $request->input('address');
        $customer->gallon_type     = $request->input('gallon_type');
        $customer->gallon_count    = $request->input('gallon_count');
        $customer->approval_status = $request->input('approval_status');
        $customer->status          = $request->input('status');

        // ✅ Update password only if provided
        if ($request->filled('password')) {
            $customer->password = bcrypt($request->input('password'));
        }

        $customer->save();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer updated successfully!');
    }


    // Delete a customer
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully');
    }
    public function archive($id)
    {
        $customer = User::findOrFail($id);
        $customer->update(['status' => 'archived']);

        return redirect()->route('admin.customers.index')->with('success', 'Customer archived successfully');
    }

    public function unarchive($id)
    {
        $customer = User::findOrFail($id);
        $customer->update(['status' => 'active']);

        return redirect()->route('admin.customers.index')->with('success', 'Customer unarchived successfully');
    }

    public function requestReactivation($id)
    {
        $customer = User::findOrFail($id);

        // Logic for sending the reactivation request (you can store this in a database or send an email)

        return redirect()->route('orders.index')->with('success', 'Reactivation request submitted.');
    }




}
