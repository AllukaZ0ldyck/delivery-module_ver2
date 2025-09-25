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
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('admin.orders.index') }}">Orders</a>
                    <a href="{{ route('admin.borrowed-gallons') }}">Borrowed Gallons</a> <!-- Admin's Link to Borrowed Gallons -->
                    <div class="dropdown px-0 mx-0">
                        <button class="border-0 bg-transparent dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Users
                        </button>
                        <ul class="dropdown-menu mt-3">
                            <li><a class="dropdown-item" href="{{ route('roles.index') }}">Roles</a></li>
                            <li><a class="dropdown-item" href="{{ route('customers.index') }}">Customers</a></li>
                            <li><a class="dropdown-item" href="{{ route('admins.index') }}">Personnels</a></li>
                        </ul>
                    </div>
                @endcan

                {{-- Customer Links --}}
                @can('customer')
                    <a href="{{ route('account-overview.index') }}">Overview</a>
                    <a href="{{ route('orders.index') }}">My Orders</a>
                    <a href="{{ route('borrow-gallon.create') }}">Borrow Gallon</a> <!-- Customer's Link to Borrow Gallon -->
                    <a href="{{ route('borrow-gallon.index') }}">My Borrowed Gallons</a> <!-- Customer's Link to My Borrowed Gallons -->
                @endcan
            </nav>

            <!-- Profile / Logout -->
            <div class="header-navigation-links d-flex gap-4">
                @can('customer')
                    <a href="{{ route('profile.index', ['user_type' => 'customer']) }}">Profile</a>
                @elsecan('admin')
                    <a href="{{ route('profile.index', ['user_type' => 'admin']) }}">Profile</a>
                @endcan

                <form action="{{ route('auth.logout') }}" method="POST" style="display:inline;" class="logout-form">
                    @csrf
                    <button type="submit" class="border-0 bg-transparent p-0 m-0 align-baseline">Logout</button>
                </form>

                <a href="#" class="avatar">
                    <img src="https://assets.codepen.io/285131/hat-man.png" alt="Avatar" />
                </a>
            </div>
        </div>

        <!-- Mobile menu button -->
        <a href="javascript:void(0)" class="button btn-navigate">
            <i class="ph-list-bold"></i>
            <span>Menu</span>
        </a>
    </div>
</header>
