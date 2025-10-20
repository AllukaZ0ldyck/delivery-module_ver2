<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Show customer orders list
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        $orders = Order::with('items.product')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show form to create new order
     */
    public function create()
    {
        $products = Product::all();
        $products = Product::where('is_active', true)->get();

        $user = Auth::user(); // ✅ Get the logged-in user

        return view('orders.create', compact('products', 'user'));
    }


    /**
     * Store a new order
     */
    

    public function store(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_address' => 'required|string|max:255',
            'delivery_date' => 'required|date|after_or_equal:today',
            'payment_method' => 'required|in:COD,GCash',
            'payment_receipt' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle GCash receipt upload
        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')->store('receipts', 'public');
        }

        $paymentStatus = $request->payment_method === 'GCash' ? 'pending_verification' : 'unpaid';

        // Calculate total
        $totalPrice = 0;
        foreach ($request->items as $item) {

            // ✅ Check if product exists AND active
            $product = Product::where('id', $item['product_id'])
                ->where('is_active', true)
                ->first();

            if (!$product) {
                return back()->with('error', 'One of the selected products is currently unavailable or under maintenance.');
            }

            // Add up total
            $totalPrice += $product->price * $item['quantity'];
        }

        // Create main order
        $order = Order::create([
            'user_id' => \Auth::id(),
            'total_price' => $totalPrice,
            'delivery_address' => $request->delivery_address,
            'delivery_date' => $request->delivery_date,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_receipt' => $receiptPath,
            'payment_status' => $paymentStatus,
        ]);

        // Create order items
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'total_price' => $product->price * $item['quantity'],
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }



    /**
     * Admin/Customer can view specific order
     */
   public function show($id)
    {
        $order = Order::with([
            'user',
            'items.product',
            'payments',
            'deliveryPersonnel'
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }





    /**
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
            return back()->with('success', 'Order cancelled successfully.');
        }
        return back()->with('error', 'Order cannot be cancelled.');
    }

    public function processPayment(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Assuming $request contains the payment information
        if ($request->payment_status == 'paid') {
            // Update payment status to 'paid'
            $order->updatePaymentStatus('paid');

            // Update delivery status if payment is successful
            $order->updateDeliveryStatus('in_transit');  // or 'pending' based on your logic

            return redirect()->route('orders.show', $order_id)
                ->with('success', 'Payment successful, delivery in progress!');
        }

        // If payment fails
        $order->updatePaymentStatus('failed');
        return redirect()->route('orders.show', $order_id)
            ->with('error', 'Payment failed, please try again.');
    }

    public function updateDeliveryStatus(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $order->updateDeliveryStatus($request->delivery_status);

        return redirect()->route('orders.show', $order_id)
            ->with('success', 'Delivery status updated successfully!');
    }

    public function simulatePayment(Request $request, $order_id)
    {
        // Find the order
        $order = Order::findOrFail($order_id);

        // Update the order status to 'paid'
        $order->update([
            'status' => 'paid',
        ]);

        // Return a response to indicate success
        return redirect()->route('orders.index')->with('success', 'Payment simulated successfully!');
    }

    public function assignDeliveryPersonnel(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validate the request
        $request->validate([
            'delivery_personnel_id' => 'required|exists:admins,id'
        ]);


        // Update the order with the assigned delivery personnel
        $order->update([
            'delivery_personnel_id' => $request->delivery_personnel_id,
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Delivery personnel assigned successfully.');
    }




}
