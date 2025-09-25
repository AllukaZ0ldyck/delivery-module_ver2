@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Customer</h2>

    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $customer->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $customer->email }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="active" @if($customer->status == 'active') selected @endif>Active</option>
                <option value="archived" @if($customer->status == 'archived') selected @endif>Archived</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Customer</button>
    </form>

    @if($customer->status == 'active')
        <form action="{{ route('admin.customers.archive', $customer->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-danger mt-3">Archive Customer</button>
        </form>
    @else
        <form action="{{ route('admin.customers.unarchive', $customer->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-success mt-3">Unarchive Customer</button>
        </form>
    @endif
</div>
@endsection
