<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AccountOverviewController extends Controller
{
    /**
     * Show the customer dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $customer = auth()->user(); // Get the logged-in customer (or any other logic to get the customer)

        // Dashboard summary
        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $totalDue = Order::where('user_id', $user->id)->where('status', 'pending')->sum('total_price');

        // Recent orders
        $recentOrders = Order::with('product')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('account-overview.index', compact(
            'totalOrders',
            'pendingOrders',
            'totalDue',
            'recentOrders'
        ));
    }

    /**
     * Show bills & payments page
     */


    public function bills(Request $request)
    {
        $user = Auth::user();

        // Get the filter from the query string (unpaid or paid)
        $filter = $request->query('filter', 'unpaid');
        $isPaid = $filter === 'paid';

        // Fetching bills (orders that are not yet paid or already paid)
        $bills = Order::with('product', 'payments')
            ->where('user_id', $user->id)
            ->where('status', 'pending')  // You can modify the status logic based on your needs
            ->latest()
            ->get();

        // Fetch payments related to those bills
        $payments = Payment::with('order')
            ->whereIn('order_id', $bills->pluck('id'))
            ->latest()
            ->get();

        // Pass the data to the appropriate view based on filter
        if ($filter == 'unpaid') {
            return view('customer.payments.unpaid', compact('bills', 'payments', 'filter'));
        } else {
            return view('customer.payments.paid', compact('bills', 'payments', 'filter'));
        }
    }




    /**
     * Show the orders page for customer.
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::with('product')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show a single order detail for customer.
     */
    public function showOrder($id)
    {
        $user = Auth::user();
        $order = Order::with('product', 'payments')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }
}
