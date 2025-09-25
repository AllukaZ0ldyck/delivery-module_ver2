@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Borrow Gallon</h2>

    <form action="{{ route('borrow-gallon.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="gallon_count">Number of Gallons</label>
            <input type="number" name="gallon_count" id="gallon_count" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Borrow Gallon</button>
    </form>
</div>
@endsection
