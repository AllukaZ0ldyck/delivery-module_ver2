@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Borrowed Gallons</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Gallon Count</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowedGallons as $borrowedGallon)
                <tr>
                    <td>{{ $borrowedGallon->user->name }}</td>
                    <td>{{ $borrowedGallon->gallon_count }}</td>
                    <td>{{ $borrowedGallon->due_date }}</td>
                    <td>{{ ucfirst($borrowedGallon->status) }}</td>
                    <td>
                        <form action="{{ route('admin.borrowed-gallons.update', $borrowedGallon->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="returned">
                            <button type="submit" class="btn btn-success btn-sm">Mark as Returned</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
