@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-uppercase fw-bold">Edit Customer</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3">Personal Information</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-bold">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="contact_no" class="form-label fw-bold">Contact Number</label>
                    <input type="text" id="contact_no" name="contact_no" value="{{ old('contact_no', $customer->contact_no) }}" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label fw-bold">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $customer->address) }}" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="gallon_type" class="form-label fw-bold">Gallon Type</label>
                    <select id="gallon_type" name="gallon_type" class="form-control">
                        <option value="">-- Select Gallon Type --</option>
                        <option value="Blue 5 Gallon" {{ old('gallon_type', $customer->gallon_type) == 'Blue 5 Gallon' ? 'selected' : '' }}>Blue 5 Gallon</option>
                        <option value="Slim 5 Gallon" {{ old('gallon_type', $customer->gallon_type) == 'Slim 5 Gallon' ? 'selected' : '' }}>Slim 5 Gallon</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="gallon_count" class="form-label fw-bold">Gallon Count</label>
                    <input type="number" id="gallon_count" name="gallon_count" value="{{ old('gallon_count', $customer->gallon_count) }}" min="0" class="form-control">
                </div>
            </div>
        </div>

        <div class="card shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3">Account Settings</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="role" class="form-label fw-bold">Role</label>
                    <input type="text" id="role" name="role" value="{{ ucfirst($customer->role) }}" class="form-control" readonly>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="approval_status" class="form-label fw-bold">Approval Status</label>
                    <select id="approval_status" name="approval_status" class="form-control">
                        <option value="pending" {{ $customer->approval_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $customer->approval_status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $customer->approval_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label fw-bold">Account Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ $customer->status == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">Update Customer</button>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary px-4">Cancel</a>
        </div>
    </form>

    <div class="mt-4">
        @if($customer->status == 'active')
            <form action="{{ route('admin.customers.archive', $customer->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Archive Customer</button>
            </form>
        @else
            <form action="{{ route('admin.customers.unarchive', $customer->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success">Unarchive Customer</button>
            </form>
        @endif
    </div>
</div>
@endsection
