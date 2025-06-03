<x-guest-layout>
    @push('styles')
        <style>
            .bg-image-vertical {
                position: relative;
                overflow: hidden;
                background-repeat: no-repeat;
                background-position: right center;
                background-size: auto 100%;
            }

            @media (min-width: 1025px) {
                .h-custom-2 {
                    height: 100%;
                }
            }
        </style>
    @endpush

    <section class="vh-100">
        <div class="container-fluid">
            <div class="row">
                <!-- Form Side -->
                <div class="col-sm-6 text-white " style="background-color: #003200;">
                    <div class="px-5 ms-xl-4">
                       <img src="{{ asset('images/bliss_logo.png') }}" alt="Logo" style="height: 70px; vertical-align: middle;" class="me-3 mt-2">
                        <span class="h1 fw-bold mb-0">Bliss Coffee</span>
                    </div>

                    <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
                        <form method="POST" action="{{ route('login') }}" style="width: 23rem;">
                            @csrf

                            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;" style="text-color: #FFFFFF;">Log in</h3>

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
                                <button class="btn btn-info btn-lg btn-block" type="submit">Login</button>
                            </div>

                            @if (Route::has('password.request'))
                                <p class="small mb-5 pb-lg-2">
                                    <a class="text-muted" href="{{ route('password.request') }}">Forgot password?</a>
                                </p>
                            @endif

                            @if (Route::has('register'))
                                <p>Don't have an account?
                                    <a href="{{ route('register') }}" class="link-info">Register here</a>
                                </p>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Image Side -->
                <div class="col-sm-6 px-0 d-none d-sm-block">
                   <img src="{{ asset('images/coffee_bg.jpg') }}"
                        alt="Login image" 
                        class="w-100 vh-100" 
                        style="object-fit: cover; object-position: left;">

                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
