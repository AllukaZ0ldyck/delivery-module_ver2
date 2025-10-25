@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-uppercase fw-bold">Customer Details</h2>

    <div class="card shadow-sm p-4 mb-4">
        <h5 class="fw-bold mb-3">Personal Information</h5>
        <div class="row">
            <div class="col-md-6 mb-2">
                <strong>Full Name:</strong> {{ $customer->name }}
            </div>
            <div class="col-md-6 mb-2">
                <strong>Email:</strong> {{ $customer->email }}
            </div>
            <div class="col-md-6 mb-2">
                <strong>Contact Number:</strong> {{ $customer->contact_no ?? 'N/A' }}
            </div>
            <div class="col-md-6 mb-2">
                <strong>Address:</strong> {{ $customer->address ?? 'N/A' }}
            </div>
            <div class="col-md-6 mb-2">
                <strong>Gallon Type:</strong> {{ $customer->gallon_type ?? 'N/A' }}
            </div>
            <div class="col-md-6 mb-2">
                <strong>Gallon Count:</strong> {{ $customer->gallon_count ?? 'N/A' }}
            </div>
        </div>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5 class="fw-bold mb-3">Account Information</h5>
        <div class="row">
            <div class="col-md-4 mb-2">
                <strong>Role:</strong> {{ ucfirst($customer->role) }}
            </div>
            <div class="col-md-4 mb-2">
                <strong>Approval Status:</strong>
                <span class="badge 
                    @if($customer->approval_status == 'approved') bg-success 
                    @elseif($customer->approval_status == 'pending') bg-warning 
                    @elseif($customer->approval_status == 'rejected') bg-danger 
                    @else bg-secondary @endif">
                    {{ ucfirst($customer->approval_status) }}
                </span>
            </div>
            <div class="col-md-4 mb-2">
                <strong>Account Created:</strong> {{ $customer->created_at->format('M d, Y') }}
            </div>
        </div>
    </div>

    @if($customer->qr_code)
    <div class="card shadow-sm p-4 mb-4">
        <h5 class="fw-bold mb-3">QR Code</h5>
        <img src="{{ asset('storage/' . $customer->qr_code) }}" 
             alt="QR Code" 
             style="width:150px; height:150px; border:1px solid #ccc; border-radius:10px;">
    </div>
    @endif

    <div class="card shadow-sm p-4 mb-4">
        <h5 class="fw-bold mb-3">Recent Orders</h5>
        @if($customer->orders->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Delivery Date</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->orders->take(5) as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>₱{{ number_format($order->total_price, 2) }}</td>
                            <td><span class="badge bg-{{ 
                                $order->status === 'delivered' ? 'success' :
                                ($order->status === 'out_for_delivery' ? 'info' :
                                ($order->status === 'pending' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span></td>
                            <td>{{ ucfirst($order->payment_status ?? 'N/A') }}</td>
                            <td>{{ $order->delivery_date ?? 'N/A' }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted mb-0">No orders found for this customer.</p>
        @endif
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5 class="fw-bold mb-3">Borrowed Gallons</h5>
        @if(isset($customer->borrowedGallons) && $customer->borrowedGallons->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Count</th>
                        <th>Status</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->borrowedGallons as $borrow)
                        <tr>
                            <td>{{ $borrow->gallon_type }}</td>
                            <td>{{ $borrow->gallon_count }}</td>
                            <td>{{ ucfirst($borrow->status) }}</td>
                            <td>{{ $borrow->due_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted mb-0">No borrowed gallons recorded.</p>
        @endif
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary px-4">← Back to Customers</a>
    </div>
</div>
@endsection
