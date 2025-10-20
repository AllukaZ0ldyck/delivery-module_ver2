<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\BorrowedGallon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class StaffPersonnelController extends Controller
{
    // MAIN DASHBOARD
    public function index()
    {
        $staff = Auth::guard('admin')->user();

        $pendingOrders = Order::where('status', 'pending')->count();
        $inTransitOrders = Order::where('status', 'out_for_delivery')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        $products = Product::all();
        $borrowedGallons = BorrowedGallon::where('status', 'borrowed')->get();
        $customers = User::where('role', 'customer')
            ->where('approval_status', 'approved')
            ->get();

        return view('staff-personnel.index', compact(
            'staff',
            'pendingOrders',
            'inTransitOrders',
            'deliveredOrders',
            'products',
            'borrowedGallons',
            'customers'
        ));
    }

    // SHOW INDIVIDUAL ENTITY DETAILS
    public function show($type, $id)
    {
        switch ($type) {
            case 'order':
                $item = Order::with(['user', 'items.product'])->findOrFail($id);
                break;

            case 'product':
                $item = Product::findOrFail($id);
                break;

            case 'customer':
                $item = User::with(['orders.items.product', 'borrowedGallons'])->findOrFail($id);
                break;

            case 'borrowed':
            case 'borrowed-gallon':
                $item = BorrowedGallon::with('user')->findOrFail($id);
                break;

            default:
                abort(404, 'Invalid type');
        }

        return view('staff-personnel.show', compact('item', 'type'));
    }

    // ORDER PROCESSING ACTIONS
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,out_for_delivery,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated successfully!');
    }

    // INVENTORY CONTROL
    public function updateInventory(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $product = Product::findOrFail($id);
        $product->stock = $request->stock;
        $product->save();

        return back()->with('success', 'Inventory updated successfully!');
    }

    // BORROWED GALLON APPROVAL
    public function approveBorrowed(Request $request, $id)
    {
        $borrowed = BorrowedGallon::findOrFail($id);
        $borrowed->status = 'approved';
        $borrowed->save();

        return back()->with('success', 'Borrowed gallon request approved!');
    }

    public function markReturned(Request $request, $id)
    {
        $borrowed = BorrowedGallon::findOrFail($id);
        $borrowed->status = 'returned';
        $borrowed->save();

        return back()->with('success', 'Borrowed gallon marked as returned!');
    }


    public function manageCustomer($id)
    {
        $customer = User::findOrFail($id);
        return view('staff-personnel.customer-manage', compact('customer'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'contact' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gallon_type' => 'nullable|string|max:100',
            'gallon_count' => 'nullable|integer|min:0',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'address' => $request->address,
            'gallon_type' => $request->gallon_type,
            'gallon_count' => $request->gallon_count,
        ]);

        return back()->with('success', 'Customer information updated successfully.');
    }

    public function regenerateQr($id)
    {
        $customer = User::findOrFail($id);

        // Generate a new secure token
        $newToken = Str::uuid();
        $customer->qr_token = $newToken;

        // ✅ Use the same working NGROK URL or app URL
        $qrLoginUrl = url(env('NGROK_URL', config('app.url')) . '/qr-login/' . $newToken);

        // ✅ Generate QR image using Endroid (no Imagick required)
        $qrCode = new QrCode($qrLoginUrl);
        $writer = new PngWriter();
        $imageData = $writer->write($qrCode)->getString();

        // ✅ Save QR image in storage
        $path = 'qrcodes/' . $customer->id . '.png';
        Storage::put( $path, $imageData);

        // ✅ Save to DB
        $customer->qr_code = $path;
        $customer->save();

        return back()->with('success', 'New QR Code generated successfully.');
    }

    public function listCustomers()
    {
        $customers = \App\Models\User::where('role', 'customer')
            ->where('approval_status', 'approved')
            ->get();

        return view('staff-personnel.customer-list', compact('customers'));
    }

}
