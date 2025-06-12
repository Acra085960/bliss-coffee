{{-- filepath: /home/acra/bliss/resources/views/auth/register.blade.php --}}
<x-guest-layout>
    @push('styles')
        <style>
        .form-container {
            background: #f3f4f6;
            color: #222;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 40px;
        }
        .form-label {
            color: #222;
        }
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
        .image-side {
            position: relative;
            background: rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }
        .image-side img {
            object-fit: cover;
            object-position: center center;
            width: 100%;
            height: 100%;
        }
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 30px;
            margin-bottom: 20px;
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
                            <form method="POST" action="{{ route('register') }}" style="width: 23rem;" class="form-container">
                                @csrf

                                <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px; color:#222;">Register</h3>

                                <!-- Name -->
                                <div class="form-outline mb-4">
                                    <input type="text" id="name" name="name"
                                        class="form-control form-control-lg"
                                        value="{{ old('name') }}" required autofocus autocomplete="name"/>
                                    <label class="form-label" for="name">Full Name</label>
                                    @error('name')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-outline mb-4">
                                    <input type="email" id="email" name="email"
                                        class="form-control form-control-lg"
                                        value="{{ old('email') }}" required autocomplete="username"/>
                                    <label class="form-label" for="email">Email address</label>
                                    @error('email')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-outline mb-4">
                                    <input type="password" id="password" name="password"
                                        class="form-control form-control-lg"
                                        required autocomplete="new-password"/>
                                    <label class="form-label" for="password">Password</label>
                                    @error('password')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-outline mb-4">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control form-control-lg"
                                        required autocomplete="new-password"/>
                                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                                    @error('password_confirmation')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="pt-1 mb-4">
                                    <button class="btn btn-info btn-lg w-100 shadow-sm" type="submit">Register</button>
                                </div>

                                <!-- Login Link -->
                                <p class="text-center">Already have an account?
                                    <a href="{{ route('login') }}" class="link-info">Login here</a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Image Side -->
                <div class="col-sm-6 px-0 d-none d-sm-block image-side">
                    <img src="{{ asset('images/coffee_bg.jpg') }}"
                        alt="Register image">
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>