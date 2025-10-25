@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Submit a Support Ticket</h2>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Message</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Ticket</button>
    </form>
</div>
@endsection
