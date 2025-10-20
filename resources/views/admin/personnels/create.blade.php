@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-uppercase fw-bold">Add New Personnel</h2>

    <form action="{{ route('admin.personnels.store') }}" method="POST">
        @csrf
        <div class="card p-4 shadow-sm">
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="user_type" class="form-label fw-bold">Role</label>
                <select id="user_type" name="user_type" class="form-control" required>
                    <option value="">-- Select Role --</option>
                    <option value="Delivery">Delivery</option>
                    <option value="staff">staff</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary px-4">Save</button>
            <a href="{{ route('admin.personnels.index') }}" class="btn btn-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
@endsection
