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
                <h1 class="fw-bold mb-2">Sign Up</h1>
                <span>or use your email to register</span>

                <!-- BASIC INFO -->
                <input type="text" name="name" placeholder="Full Name" required />
                <input type="text" name="contact" placeholder="Contact Number" required />
                <input type="email" name="email" placeholder="Email Address" required />
                <input type="text" name="address" placeholder="Home Address" required />

                <!-- MULTIPLE GALLON TYPES -->
                <h5 class="fw-bold mt-3">Owned Gallons</h5>

                <div id="gallon-wrapper">

                    <!-- Default Row -->
                    <div class="gallon-row mb-2 d-flex gap-2 align-items-center">
                        <select name="gallons[0][type]" class="form-select" required>
                            <option value="">Select Gallon Type</option>
                            <option value="Blue 5 Gallon">Blue 5 Gallon</option>
                            <option value="Slim 5 Gallon">Slim 5 Gallon</option>
                            <option value="Round 5 Gallon">Round 5 Gallon</option>
                        </select>

                        <input type="number" name="gallons[0][qty]" class="form-control"
                            placeholder="Qty" min="1" required />

                        <button type="button" class="btn btn-danger remove-gallon d-none">X</button>
                    </div>

                </div>

                <button type="button" id="add-gallon" class="btn btn-secondary btn-sm mb-3">
                    + Add Gallon Type
                </button>

                <!-- PASSWORD FIELDS -->
                <input type="password" name="password" placeholder="Password" required />
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required />

                <button class="mt-4">Sign Up</button>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <img src="{{asset('images/water_delivery.webp')}}" alt="" class="w-100">
                    <p>To keep connected with us please login with your personal info</p>
                    <a href="/login" class="btn btn-primary border-2 fs-6 px-5 py-3 text-white fw-bold text-uppercase fw-bold" id="signIn">Sign In</a>
                </div>

                <div class="overlay-panel overlay-right">
                    <h1>AquaTek Water Station</h1>
                    <p>
                        Are you ready to view your water bills and place orders?
                        Create your account now!
                    </p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RESPONSIVE MOBILE FIX -->
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

<style>
    /* Make entire form area scrollable */
    .sign-up-container {
        overflow-y: auto;
        max-height: 100vh;
        padding-bottom: 30px;
    }

    /* Prevent container from blocking scroll */
    #container {
        overflow-y: auto;
        max-height: 100vh;
    }

    /* Fix for form overflowing on smaller screens */
    .login {
        height: 100vh;
        overflow-y: auto;
    }

    /* MOBILE FIXES */
    @media (max-width: 600px) {
        .sign-up-container {
            max-height: 90vh;
            overflow-y: auto;
        }

        #container {
            overflow-y: auto !important;
        }

        .login {
            overflow-y: auto !important;
        }
    }
</style>


<!-- MULTIPLE GALLON JS -->
<script>
    let index = 1;

    document.getElementById('add-gallon').addEventListener('click', function () {

        let html = `
            <div class="gallon-row mb-2 d-flex gap-2 align-items-center">
                <select name="gallons[${index}][type]" class="form-select" required>
                    <option value="">Select Gallon Type</option>
                    <option value="Blue 5 Gallon">Blue 5 Gallon</option>
                    <option value="Slim 5 Gallon">Slim 5 Gallon</option>
                    <option value="Round 5 Gallon">Round 5 Gallon</option>
                </select>

                <input type="number" name="gallons[${index}][qty]" 
                       class="form-control" placeholder="Qty" min="1" required />

                <button type="button" class="btn btn-danger remove-gallon">X</button>
            </div>
        `;

        document.getElementById('gallon-wrapper').insertAdjacentHTML('beforeend', html);

        index++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-gallon')) {
            e.target.closest('.gallon-row').remove();
        }
    });
</script>

@endsection
