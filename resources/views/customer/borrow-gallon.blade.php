@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Borrow Gallons</h2>

    <form action="{{ route('borrow-gallon.store') }}" method="POST">
        @csrf

        <h5 class="fw-bold mt-3">Borrowed Gallons</h5>

        <div id="borrow-wrapper">

            <!-- Default Row -->
            <div class="borrow-row d-flex gap-2 mb-2 align-items-center">

                <select name="borrows[0][gallon_type]" class="form-select" required>
                    <option value="">Select Gallon Type</option>
                    <option value="Blue 5 Gallon">Blue 5 Gallon</option>
                    <option value="Slim 5 Gallon">Slim 5 Gallon</option>
                    <option value="Round 5 Gallon">Round 5 Gallon</option>
                </select>

                <input type="number" name="borrows[0][gallon_count]" 
                       class="form-control" placeholder="Qty" min="1" required />

                <input type="date" name="borrows[0][due_date]" 
                       class="form-control" required />

                <button type="button" class="btn btn-danger remove-row d-none">X</button>

            </div>

        </div>

        <button type="button" id="add-row" class="btn btn-secondary btn-sm mb-3">
            + Add More
        </button>

        <button type="submit" class="btn btn-primary mt-2">Submit Borrow Request</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let index = 1;

    document.getElementById('add-row').addEventListener('click', function () {
        let html = `
            <div class="borrow-row d-flex gap-2 mb-2 align-items-center">

                <select name="borrows[${index}][gallon_type]" class="form-select" required>
                    <option value="">Select Gallon Type</option>
                    <option value="Blue 5 Gallon">Blue 5 Gallon</option>
                    <option value="Slim 5 Gallon">Slim 5 Gallon</option>
                    <option value="Round 5 Gallon">Round 5 Gallon</option>
                </select>

                <input type="number" name="borrows[${index}][gallon_count]" 
                       class="form-control" placeholder="Qty" min="1" required />

                <input type="date" name="borrows[${index}][due_date]" 
                       class="form-control" required />

                <button type="button" class="btn btn-danger remove-row">X</button>

            </div>
        `;

        document.getElementById('borrow-wrapper').insertAdjacentHTML('beforeend', html);
        index++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.borrow-row').remove();
        }
    });

});
</script>

@endsection
