@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Staff Dashboard</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5>Pending Orders</h5>
                    <h3>{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5>In Transit</h5>
                    <h3>{{ $inTransitOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5>Delivered</h5>
                    <h3>{{ $deliveredOrders }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-4">Inventory Overview</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Type</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->type ?? 'N/A' }}</td>
                    <td>{{ $p->stock ?? 0 }}</td>
                    <td>₱{{ number_format($p->price, 2) }}</td>
                    <td>
                        @if($p->is_active)
                            <span class="badge bg-success">Available</span>
                        @else
                            <span class="badge bg-danger">Maintenance</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="mt-4">Borrowed Gallons</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Type</th>
                <th>Count</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowedGallons as $b)
                <tr>
                    <td>{{ $b->user->name }}</td>
                    <td>{{ $b->gallon_type }}</td>
                    <td>{{ $b->gallon_count }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->due_date)->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="mt-4">Customers</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>QR Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email }}</td>
                    <td>
                        @if($c->qr_code)
                            <img src="{{ asset('storage/'.$c->qr_code) }}" alt="QR" width="50">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('staff.show', ['type' => 'customer', 'id' => $c->id]) }}" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
