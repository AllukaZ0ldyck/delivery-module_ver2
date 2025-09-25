@extends('layouts.app')

@section('content')
<main class="main">
    <div class="responsive-wrapper">
        <div class="main-header">
            <h1 class="mb-4 text-uppercase">Account Overview</h1>
        </div>

        <div>
            <h4>Welcome, {{ auth()->user()->name }}</h4>
            <p>Account Number: {{ auth()->user()->account_no }}</p>
            <p>Balance: ₱{{ number_format($balance, 2) }}</p>
        </div>
    </div>
</main>
@endsection
