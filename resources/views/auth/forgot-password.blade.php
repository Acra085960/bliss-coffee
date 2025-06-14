{{-- filepath: /home/acra/bliss/resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body>
    <x-guest-layout>
        <section class="vh-100">
            <div class="container-fluid">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-6">
                        <div class="form-container">
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/bliss_logo.png') }}" alt="Logo" style="height: 60px;">
                                <h1 class="h3 mt-3">Forgot Password?</h1>
                                <p class="text-muted">Enter your email address and we'll send you a password reset link.</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success mb-4">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <!-- Email Address -->
                                <div class="form-outline mb-4">
                                    <input type="email" id="email" name="email"
                                        class="form-control form-control-lg"
                                        value="{{ old('email') }}" required autofocus />
                                    <label class="form-label" for="email">Email address</label>
                                    @error('email')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('login') }}" class="link-info">
                                        ← Back to Login
                                    </a>
                                    <button class="btn btn-info" type="submit">
                                        Send Reset Link
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-guest-layout>

    @livewireScripts
</body>
</html>