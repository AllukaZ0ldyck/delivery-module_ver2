@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Customer Details</h2>

    <p><strong>Name:</strong> {{ $customer->name }}</p>
    <p><strong>Email:</strong> {{ $customer->email }}</p>

    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Back to Customers</a>
</div>
@endsection
