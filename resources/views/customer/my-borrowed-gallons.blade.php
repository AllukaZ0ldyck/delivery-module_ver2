@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Borrowed Gallons</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Gallon Count</th>
                <th>Borrowed At</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowedGallons as $borrowedGallon)
                <tr>
                    <td>{{ $borrowedGallon->gallon_count }}</td>
                    <td>{{ $borrowedGallon->borrowed_at }}</td>
                    <td>{{ $borrowedGallon->due_date }}</td>
                    <td>{{ ucfirst($borrowedGallon->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
