<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Show list of all customers
    public function index()
    {
        // Retrieve all customers
        // $customers = User::all();
        $customers = \App\Models\User::where('role', '!=', 'delivery_personnel')->get();
        return view('admin.customers.index', compact('customers'));
    }

    // Show the details of a single customer
    public function show($id)
    {
        // Find the customer by ID
        $customer = User::findOrFail($id);
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

        // Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Update the customer information
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');

        // Only update the password if it's provided
        if ($request->has('password') && $request->input('password') !== '') {
            $customer->password = bcrypt($request->input('password'));
        }

        $customer->save();

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully');
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
