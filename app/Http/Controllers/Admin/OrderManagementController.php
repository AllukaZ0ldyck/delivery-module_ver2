<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User;

class OrderManagementController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'product')->latest()->paginate(10);
        $deliveryPersonnel = User::where('role', 'delivery_personnel')->get(); // Assuming 'role' is how you identify delivery personnel

        return view('admin.orders.index', compact('orders', 'deliveryPersonnel'));
    }

    public function show($id)
    {
        $order = Order::with('user', 'product', 'payments')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function assignDeliveryPersonnel(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validate the incoming request
        $request->validate([
            'delivery_personnel_id' => 'required|exists:users,id'
        ]);

        // Update the order with the selected delivery personnel
        $order->delivery_personnel_id = $request->delivery_personnel_id;
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Delivery personnel assigned successfully');
    }

}
