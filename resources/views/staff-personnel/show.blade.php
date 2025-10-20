@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3 text-capitalize">{{ ucfirst($type) }} Details</h2>

    {{-- 🔹 ORDER DETAILS --}}
    @if(isset($item->total_price))
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Order Details</div>
            <div class="card-body">
                <p><strong>Order ID:</strong> {{ $item->id }}</p>
                <p><strong>Customer:</strong> {{ $item->user->name ?? 'N/A' }}</p>
                <p><strong>Status:</strong> {{ ucfirst($item->status ?? 'N/A') }}</p>
                <p><strong>Total:</strong> ₱{{ number_format($item->total_price ?? 0, 2) }}</p>
                <p><strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($item->delivery_date)->format('M d, Y') }}</p>

                <form method="POST" action="{{ route('staff.updateOrderStatus', $item->id) }}" class="mt-3">
                    @csrf
                    <label><strong>Update Order Status:</strong></label>
                    <select name="status" class="form-select w-auto d-inline-block">
                        <option value="confirmed">Confirmed</option>
                        <option value="out_for_delivery">Out for Delivery</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <button type="submit" class="btn btn-success btn-sm">Update</button>
                </form>
            </div>
        </div>
    @endif


    {{-- 🔹 PRODUCT DETAILS --}}
    @if(isset($item->price))
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Product Information</div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $item->name }}</p>
                <p><strong>Price:</strong> ₱{{ number_format($item->price, 2) }}</p>
                <p><strong>Stock:</strong> {{ $item->stock }}</p>

                <form method="POST" action="{{ route('staff.updateInventory', $item->id) }}" class="mt-3">
                    @csrf
                    <label><strong>Update Stock:</strong></label>
                    <input type="number" name="stock" class="form-control w-auto d-inline-block" value="{{ $item->stock }}" min="0">
                    <button type="submit" class="btn btn-primary btn-sm">Update Stock</button>
                </form>
            </div>
        </div>
    @endif


    {{-- 🔹 CUSTOMER DETAILS --}}
    @if(isset($item->email))
        <div class="card mb-4">
            <div class="card-header bg-info text-white">Customer Information</div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $item->name }}</p>
                <p><strong>Email:</strong> {{ $item->email ?? 'N/A' }}</p>
                <p><strong>Contact:</strong> {{ $item->contact ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $item->address ?? 'N/A' }}</p>

                <p><strong>QR Code:</strong></p>
                @if($item->qr_code)
                    <img src="{{ asset('storage/' . $item->qr_code) }}" width="150">
                @else
                    <span class="text-muted">No QR Code available</span>
                @endif
            </div>
        </div>

        {{-- Orders of this Customer --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Customer Orders</div>
            <div class="card-body">
                @if(isset($item->orders) && $item->orders->count())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Delivery Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ ucfirst($order->status) }}</td>
                                    <td>₱{{ number_format($order->total_price, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No orders found.</p>
                @endif
            </div>
        </div>

        {{-- Borrowed Gallons of this Customer --}}
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">Borrowed Gallons</div>
            <div class="card-body">
                @if(isset($item->borrowedGallons) && $item->borrowedGallons->count())
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Count</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->borrowedGallons as $gallon)
                                <tr>
                                    <td>{{ $gallon->gallon_type }}</td>
                                    <td>{{ $gallon->gallon_count }}</td>
                                    <td>{{ \Carbon\Carbon::parse($gallon->due_date)->format('M d, Y') }}</td>
                                    <td>{{ ucfirst($gallon->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No borrowed gallons.</p>
                @endif
            </div>
        </div>
    @endif


    {{-- 🔹 BORROWED GALLON DETAILS --}}
    @if(isset($item->gallon_type))
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">Borrowed Gallon Record</div>
            <div class="card-body">
                <p><strong>Customer:</strong> {{ $item->user->name ?? 'N/A' }}</p>
                <p><strong>Type:</strong> {{ $item->gallon_type }}</p>
                <p><strong>Count:</strong> {{ $item->gallon_count }}</p>
                <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>

                @if($item->status === 'pending')
                    <form method="POST" action="{{ route('staff.approveBorrowed', $item->id) }}" style="display:inline;">
                        @csrf
                        <button class="btn btn-success btn-sm">Approve</button>
                    </form>
                @elseif($item->status === 'approved')
                    <form method="POST" action="{{ route('staff.markReturned', $item->id) }}" style="display:inline;">
                        @csrf
                        <button class="btn btn-info btn-sm">Mark as Returned</button>
                    </form>
                @endif
            </div>
        </div>
    @endif


    <a href="{{ route('staff.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
