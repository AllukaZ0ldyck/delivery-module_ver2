@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Borrow Gallon</h2>

    <form action="{{ route('borrow-gallon.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="gallon_type">Gallon Type</label>
            <select name="gallon_type" id="gallon_type" class="form-control" required>
                <option value="">-- Select Gallon Type --</option>
                <option value="Blue 5 Gallon">Blue 5 Gallon</option>
                <option value="Slim 5 Gallon">Slim 5 Gallon</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="gallon_count">Number of Gallons</label>
            <input type="number" name="gallon_count" id="gallon_count" class="form-control" required min="1">
        </div>

        <div class="form-group mb-3">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Submit Borrow Request</button>
    </form>
</div>
@endsection
