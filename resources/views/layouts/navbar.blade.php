<header class="header">
    <div class="header-content responsive-wrapper">
        <!-- Logo -->
        <div class="header-logo">
            <a href="#" class="nav-link text-uppercase fw-bold">
                <img src="{{ asset('images/water_logo.webp') }}" alt="Logo" style="width: 100px;">
            </a>
        </div>

        <!-- Navigation -->
        <div class="header-navigation">
            <div class="close-icon">
                <i class='bx bx-x'></i>
            </div>

            <nav class="header-navigation-links d-flex gap-4">
                {{-- Admin Links --}}
                @can('admin')
                    <a href="{{ route('admin.dashboard') }}">Overview</a>
                    <a href="{{ route('admin.orders.index') }}">Orders</a>
                    <a href="{{ route('admin.products.index') }}">Products</a>
                    <a href="{{ route('admin.borrowed-gallons') }}">Borrowed Gallons</a>
                    <div class="dropdown px-0 mx-0">
                        <button class="border-0 bg-transparent dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Users
                        </button>
                        <ul class="dropdown-menu mt-3">
                            <li><a class="dropdown-item" href="{{ route('admin.customers.pending') }}">Pending Customers</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.customers.index') }}">Approved Customers</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.personnels.index') }}">Personnels</a></li>
                        </ul>
                    </div>
                    <a href="{{ route('admin.reports.index') }}">Reports</a>

                @endcan

                {{-- ✅ Staff Navbar Links --}}
                @if(Auth::guard('admin')->check() && strtolower(Auth::guard('admin')->user()->user_type) === 'staff')
                    <li><a href="{{ route('staff.index') }}">Staff Dashboard</a></li>
                    <li><a href="{{ route('staff.customer.list') }}">Manage Customers</a></li>
                @endif



                {{-- Customer Links --}}
                @if(Auth::user() && Auth::user()->role === 'customer')
                    <a href="{{ route('account-overview.index') }}">Overview</a>
                    <a href="{{ route('orders.index') }}">My Orders</a>
                    <div class="dropdown px-0 mx-0">
                        <button class="border-0 bg-transparent dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Gallons
                        </button>
                        <ul class="dropdown-menu mt-3">
                            <li><a href="{{ route('borrow-gallon.create') }}">Borrow Gallon</a></li> 
                            <li><a href="{{ route('borrow-gallon.index') }}">My Gallons</a></li>
                        </ul>
                    </div>
                    
                @endif

            </nav>

            <!-- Profile / Logout -->
            <div class="header-navigation-links d-flex gap-4">
                @php
                    $admin = Auth::guard('admin')->user();
                    $user = Auth::guard('web')->user();
                    $profile = $admin ?? $user;
                @endphp

                @if($admin)
                    <a href="{{ route('role.profile.show', ['role' => strtolower($admin->user_type)]) }}">Profile</a>
                @elseif($user)
                    <a href="{{ route('profile.index', ['role' => 'customer']) }}">Profile</a>
                @endif



                <form action="{{ route('auth.logout') }}" method="POST" style="display:inline;" class="logout-form">
                    @csrf
                    <button type="submit" class="border-0 bg-transparent p-0 m-0 align-baseline">Logout</button>
                </form>

                @if($profile)
                    <a href="#" class="avatar">
                        <img 
                            src="{{ $profile->profile_picture 
                                ? asset('storage/' . $profile->profile_picture) 
                                : asset('images/default-avatar.webp') }}" 
                            alt="Avatar" 
                            class="rounded-circle" 
                            style="width:40px; height:40px; object-fit:cover;"
                        />
                    </a>
                @endif

            </div>
        </div>

        <!-- Mobile menu button -->
        <a href="javascript:void(0)" class="button btn-navigate">
            <i class="ph-list-bold"></i>
            <span>Menu</span>
        </a>
    </div>
</header>
