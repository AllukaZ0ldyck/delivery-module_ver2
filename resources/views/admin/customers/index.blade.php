@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">All Customers</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Last Order</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>
                    @if($customer->orders->count() > 0)
                        @php
                            $lastOrder = $customer->orders()->latest()->first();
                            $daysAgo = \Carbon\Carbon::parse($lastOrder->created_at)->diffInDays(\Carbon\Carbon::now());
                        @endphp
                        Last order {{ $daysAgo }} days ago
                    @else
                        No orders yet
                    @endif
                </td>
                <td>
                    @if($customer->status === 'active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Archived</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
