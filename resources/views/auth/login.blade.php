<x-guest-layout>
    @push('styles')
        <style>
            .form-control {
            background-color: #f5f5dc;
            color: #003200;
            border-radius: 10px;
            padding: 10px;
        }

           .form-control:focus {
            border-color: #A9744F;
            box-shadow: 0 0 0 0.2rem rgba(169, 116, 79, 0.25);
        }

            .btn-info {
            background-color: #A9744F;
            border: none;
            color: white;
            transition: 0.3s ease;
            border-radius: 10px;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
        }

             .btn-info:hover {
            background-color: #7B5130;
        }

            .link-info {
                color: #c7b299;
                text-decoration: none;
            }

            .link-info:hover {
                color: #e0cfa9;
                text-decoration: underline;
            }

            .form-label {
            color: #222; /* Make label text dark */
        }

            /* Form container with box shadow */
            .form-container {
            background: #fff; /* Make the form background white */
            color: #222;      /* Make the text dark */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 40px;
        }

            /* Darker overlay for the image side */
            .image-side {
                position: relative;
                background: rgba(0, 0, 0, 0.5); /* Darker overlay */
                overflow: hidden;  /* Ensures image stays within bounds */
            }

            /* Ensuring the background image fits properly */
            .image-side img {
                object-fit: cover;
                object-position: center center; /* Centers the image */
                width: 100%;
                height: 100%;
            }

            /* Styling for the logo and text */
            .logo-container {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-top: 30px;
                margin-bottom: 20px; /* Added some spacing between the logo and text */
            }

            .logo-container img {
                margin-right: 15px;
                height: 70px;
            }

            .logo-container h1 {
                font-size: 2.5rem;
                color: white;
                font-weight: bold;
            }

            /* Centering the Login form */
            .login-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100%;
            }
        </style>
    @endpush

    <section class="vh-100">
        <div class="container-fluid">
            <div class="row">
                <!-- Form Side -->
                <div class="col-sm-6" style="background: linear-gradient(180deg, #003200 0%, #004d26 70%);">
                    <div class="login-container">
                        <div class="logo-container">
                            <img src="{{ asset('images/bliss_logo.png') }}" alt="Logo">
                            <span class="h1 fw-bold mb-0">Bliss Coffee</span>
                        </div>

                        <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
                            <form method="POST" action="{{ route('login') }}" style="width: 23rem;" class="form-container">
                                @csrf
                                @if (session('status'))
    <div class="alert alert-danger">{{ session('status') }}</div>
@endif
                                @if (session('error') == 'email-not-verified')
                                    <div class="alert alert-warning mb-3" style="color:#b45309; background:#fef3c7; border-radius:8px; padding:10px;">
                                        Email Anda belum diverifikasi. Silakan cek email Anda dan klik link verifikasi.
                                    </div>
                                @endif
                                <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px; color:#222;">Log in</h3>

                                <!-- Email -->
                                <div class="form-outline mb-4">
                                    <input type="email" id="email" name="email"
                                        class="form-control form-control-lg"
                                        value="{{ old('email') }}" required autofocus autocomplete="username"/>
                                    <label class="form-label" for="email">Email address</label>
                                    @error('email')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-outline mb-4">
                                    <input type="password" id="password" name="password"
                                        class="form-control form-control-lg"
                                        required autocomplete="current-password"/>
                                    <label class="form-label" for="password">Password</label>
                                    @error('password')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Remember Me -->
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                    <label class="form-check-label" for="remember_me">Remember me</label>
                                </div>

                                <!-- Submit -->
                                <div class="pt-1 mb-4">
                                    <button class="btn btn-info btn-lg w-100 shadow-sm" type="submit">Login</button>
                                </div>

                                <!-- Forgot Password -->
                                @if (Route::has('password.request'))
                                    <p class="small mb-5 pb-lg-2">
                                        <a class="link-info" href="{{ route('password.request') }}">Forgot password?</a>
                                    </p>
                                @endif

                                <!-- Register -->
                                @if (Route::has('register'))
                                    <p>Don't have an account?
                                        <a href="{{ route('register') }}" class="link-info">Register here</a>
                                    </p>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Image Side -->
                <div class="col-sm-6 px-0 d-none d-sm-block image-side">
                    <img src="{{ asset('images/coffee_bg.jpg') }}"
                        alt="Login image">
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
