<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;

class OrderManagementController extends Controller
{
    public function index()
    {
        // ✅ Optional: filter from query string (e.g., ?filter=pending_verification)
        $filter = request('filter');

        // ✅ Build the base query with relationships (multi-product safe)
        $ordersQuery = Order::with(['user', 'items.product', 'deliveryPersonnel'])
            ->orderBy('created_at', 'desc');

        // ✅ Apply filter if needed
        if ($filter === 'pending_verification') {
            $ordersQuery->where('payment_status', 'pending_verification');
        }

        // ✅ Paginate results
        $orders = $ordersQuery->paginate(10);

        // ✅ Fetch delivery personnel from admins table
        $deliveryPersonnel = Admin::where('user_type', 'Delivery')->get();

        return view('admin.orders.index', compact('orders', 'deliveryPersonnel'));
    }

    public function show($id)
    {
        $order = Order::with([
            'user',
            'items.product',      // ✅ new relationship for multi-product orders
            'deliveryPersonnel',  // optional: show assigned delivery person
            'payments'            // keep if you have a payments table
        ])->findOrFail($id);

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
            'delivery_personnel_id' => 'required|exists:admins,id'
        ]);

        // Update the order with the selected delivery personnel
        $order->delivery_personnel_id = $request->delivery_personnel_id;
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Delivery personnel assigned successfully');
    }



    public function verifyPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, ['paid', 'declined'])) {
            return back()->with('error', 'Invalid status update.');
        }

        $order->update([
            'payment_status' => $status,
            'status' => $status === 'paid' ? 'approved' : 'pending',
        ]);

        $message = $status === 'paid'
            ? 'Payment verified successfully and order confirmed.'
            : 'Payment declined.';

        return back()->with('success', $message);
    }



}
