@extends('layouts.auth')

@section('content')

<div class="login">
    <div class="container right-panel-active scroll" id="container">
        <div class="form-container sign-up-container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1 class="fw-bold mb-2">Sign in</h1>
                <span>or use your email for registration</span>
                <input type="text" name="name" placeholder="Full Name" required />
                <input type="text" name="contact" placeholder="Contact Number" required />
                <input type="email" name="email" placeholder="Email Address" required />
                <input type="text" name="address" placeholder="Home Address" required />

                <select name="gallon_type" required>
                    <option value="">Select Gallon Type</option>
                    <option value="Blue 5 Gallon">Blue 5 Gallon</option>
                    <option value="Slim 5 Gallon">Slim 5 Gallon</option>
                </select>

                <input type="number" name="gallon_count" placeholder="Number of Gallons" required />

                <input type="password" name="password" placeholder="Password" required />
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required />

                <button class="mt-4">Sign Up</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <img src="{{asset('images/water_delivery.webp')}}" alt="" srcset="" class="w-100">
                    <p>To keep connected with us please login with your personal info</p>
                    <a href="/login" class="btn btn-primary border-2 fs-6 px-5 py-3 text-white fw-bold text-uppercase fw-bold" id="signIn">Sign In</a>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>AquaTek Water Station</h1>
                    <p>Are you ready to view your water bills? and proceed to payments? Start now by creating an account!</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    @media(min-width: 0px) and (max-width: 600px) {
        .overlay-container {
            display: none;
        }

        .login {
            width: 90%;
            display: flex;
            margin: auto !important;
            justify-content: center;
        }

        .login .sign-up-container {
            transform: none !important;
            width: 100%;
        }

        .login form {
            padding: 20px;
        }
    }
</style>
@endsection
