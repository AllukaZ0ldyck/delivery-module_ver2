<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DeliveryPersonnelController extends Controller
{
    public function index()
    {
        // Fetch orders assigned to the logged-in delivery personnel
        $orders = Order::where('delivery_personnel_id', Auth::id()) // Assuming delivery_personnel_id links the order to a delivery personnel
                    ->latest() // Optional: Sort by the latest orders
                    ->get();

        // Debugging: Log orders to ensure they're fetched correctly
        \Log::info('Orders for Delivery Personnel: ', $orders->toArray());

        // Pass the orders to the view
        return view('delivery.index', compact('orders')); // Passing $orders to the view
    }

    public function show($id)
    {
        // Fetch the order by its ID
        $order = Order::findOrFail($id);

        // Pass the order to the view
        return view('delivery.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Fetch the order
        $order = Order::findOrFail($id);

        // Update the order status
        $order->status = $request->input('status');
        $order->save();

        // Redirect back to the order list with a success message
        return redirect()->route('delivery-personnel.index')->with('success', 'Order status updated successfully!');
    }
}
