@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Gallon Inventory Dashboard</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Total Gallons</h5>
                <h3>{{ $totalGallons }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Total Refills</h5>
                <h3>{{ $totalRefills }}</h3>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Gallon usage stats can be displayed as charts here -->
    </div>
</div>
@endsection
