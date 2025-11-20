@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">My Profile</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Profile Form --}}
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-md-6">
                {{-- Full Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Full Name</label>
                    <input type="text" class="form-control" name="name" 
                        value="{{ auth()->user()->name }}" required>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" class="form-control" name="email" 
                        value="{{ auth()->user()->email }}" required>
                </div>

                {{-- Contact Number --}}
                <div class="mb-3">
                    <label for="contact" class="form-label fw-bold">Contact Number</label>
                    <input type="text" class="form-control" name="contact" 
                        value="{{ auth()->user()->contact }}" required>
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label for="address" class="form-label fw-bold">Address</label>
                    <textarea name="address" class="form-control" rows="2" required>{{ auth()->user()->address }}</textarea>
                </div>

                 {{-- Password Update --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">New Password (optional)</label>
                    <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
                </div>

                {{-- QR Code --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">QR Code</label><br>
                    @if(auth()->user()->qr_code)
                        <img src="{{ asset('storage/' . auth()->user()->qr_code) }}" 
                             alt="QR Code" 
                             style="width:150px;height:150px;border:1px solid #ccc;border-radius:8px;">
                    @else
                        <p class="text-muted">No QR Code available.</p>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Owned Gallons</label>

                    @forelse(auth()->user()->gallons as $g)
                        <div class="border rounded p-2 mb-2 bg-light">
                            <strong>{{ $g->gallon_type }}</strong><br>
                            Quantity Owned: {{ $g->quantity }}
                        </div>
                    @empty
                        <p class="text-muted">No registered gallons.</p>
                    @endforelse
                </div>


                <div class="mb-3">
                    <label class="form-label fw-bold">Borrowed Gallons</label>

                    @php
                        $borrowed = auth()->user()->borrowedGallons()
                                        ->where('status', '!=', 'returned')
                                        ->get();
                    @endphp

                    @forelse($borrowed as $b)
                        <div class="border rounded p-2 mb-2 bg-warning-subtle">
                            <strong>{{ $b->gallon_type }}</strong><br>
                            Borrowed: {{ $b->gallon_count }} pcs<br>
                            Due: {{ \Carbon\Carbon::parse($b->due_date)->format('M d, Y') }}<br>
                            Status: <span class="badge bg-info">{{ ucfirst($b->status) }}</span>
                        </div>
                    @empty
                        <p class="text-muted">No borrowed gallons.</p>
                    @endforelse
                </div>


                <div class="mb-3">
                    <label class="form-label fw-bold">Available Gallons</label>

                    @php
                        $owned = auth()->user()->gallons->groupBy('gallon_type')
                                    ->map->sum('quantity');

                        $borrow = auth()->user()->borrowedGallons()
                                    ->where('status', 'pending')
                                    ->orWhere('status', 'approved')
                                    ->get()
                                    ->groupBy('gallon_type')
                                    ->map->sum('gallon_count');

                        $available = [];

                        foreach ($owned as $type => $count) {
                            $available[$type] = $count - ($borrow[$type] ?? 0);
                        }
                    @endphp

                    @forelse($available as $type => $count)
                        <div class="p-2 border rounded bg-success-subtle mb-2">
                            <strong>{{ $type }}</strong>  
                            → Available: {{ $count }} pcs
                        </div>
                    @empty
                        <p class="text-muted">No available gallons.</p>
                    @endforelse
                </div>

            </div>
        </div>

        {{-- Submit Button --}}
        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary px-4">Update Profile</button>
        </div>
    </form>

    {{-- Last Order Section --}}
    <div class="mt-5">
        <h5 class="fw-bold">Last Order</h5>
        @php
            $lastOrder = auth()->user()->orders()->latest()->first();
            $lastOrderDays = $lastOrder ? \Carbon\Carbon::parse($lastOrder->created_at)->diffInDays(\Carbon\Carbon::now()) : null;
        @endphp
        @if($lastOrder)
            <p>Last order: {{ $lastOrderDays }} days ago</p>
            <p>Status: {{ ucfirst($lastOrder->status) }}</p>
        @else
            <p>No orders yet.</p>
        @endif
    </div>
</div>
@endsection
