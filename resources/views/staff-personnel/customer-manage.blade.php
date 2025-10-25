@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Manage Customer - {{ $customer->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Customer Information</div>
        <div class="card-body">
            <form method="POST" action="{{ route('staff.customer.update', $customer->id) }}">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><strong>Full Name</strong></label>
                        <input type="text" name="name" value="{{ $customer->name }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label><strong>Email</strong></label>
                        <input type="email" name="email" value="{{ $customer->email }}" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><strong>Contact</strong></label>
                        <input type="text" name="contact" value="{{ $customer->contact }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label><strong>Address</strong></label>
                        <input type="text" name="address" value="{{ $customer->address }}" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><strong>Gallon Type</strong></label>
                        <select name="gallon_type" class="form-control">
                            <option value="">Select Type</option>
                            <option value="Blue 5 Gallon" {{ $customer->gallon_type === 'Blue 5 Gallon' ? 'selected' : '' }}>Blue 5 Gallon</option>
                            <option value="Slim 5 Gallon" {{ $customer->gallon_type === 'Slim 5 Gallon' ? 'selected' : '' }}>Slim 5 Gallon</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label><strong>Gallon Count</strong></label>
                        <input type="number" name="gallon_count" value="{{ $customer->gallon_count }}" class="form-control" min="0">
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Update Information</button>
            </form>
        </div>
    </div>

    {{-- QR Code Section --}}
    <div class="card">
        <div class="card-header bg-dark text-white">QR Code</div>
        <div class="card-body text-center">
            @if($customer->qr_code)
                <img src="{{ asset('storage/' . $customer->qr_code) }}" alt="QR Code" width="200" class="mb-3">
            @else
                <p class="text-muted">No QR Code found.</p>
            @endif

            <form method="POST" action="{{ route('staff.customer.regenerateQr', $customer->id) }}">
                @csrf
                <button type="submit" class="btn btn-primary">Regenerate QR Code</button>
            </form>
        </div>
    </div>

    <a href="{{ route('staff.index') }}" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
@endsection
