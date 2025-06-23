{{-- filepath: /home/acra/bliss/resources/views/auth/login.blade.php --}}
<x-guest-layout>
    @push('styles')
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(180deg, #003200 0%, #004d26 100%);
            position: relative;
        }
        .login-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: url('{{ asset('images/coffee_bg.jpg') }}') center center/cover no-repeat;
            opacity: 0.18;
        }
        .login-center-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }
        .login-card {
   background: #223322;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    padding: 2.2rem 2rem 1.2rem 2rem;
    width: 100%;
    max-width: 370px;
    display: flex;
    flex-direction: column;
}
        .login-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 0.3rem;
            margin-top: 0.3rem;
        }
        .login-logo img {
            height: 54px;
            margin-bottom: 0.2rem;
        }
       .login-logo span {
    font-size: 1.7rem;
    font-weight: bold;
    color: #fff !important; /* putih */
    letter-spacing: 1px;
    margin-bottom: 0.2rem;
    }
    .login-separator {
        width: 100%;
        border: none;
        border-top: 2px solid #a3a3a3;
        margin: 0 0 1.1rem 0;
    }
    .fw-normal, .login-title {
        color: #fff !important; /* putih untuk judul Login */
    }
        .form-label {
            color: #fff;
            font-weight: 500;
        }
        .form-control {
            background: #f5f5dc;
            border-radius: 8px;
            color: #003200;
        }
        .form-control:focus {
            border-color: #A9744F;
            box-shadow: 0 0 0 0.2rem rgba(169, 116, 79, 0.15);
        }
        .btn-login {
            background: #A9744F;
            color: #fff;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background: #7B5130;
        }
        .login-footer {
            width: 100%;
            margin-top: 1.2rem;
            text-align: center;
        }
        .login-footer .link-info {
            color: #A9744F;
            text-decoration: none;
            margin: 0 0.5rem;
        }
        .login-footer .link-info:hover {
            color: #7B5130;
            text-decoration: underline;
        }
        @media (max-width: 600px) {
    .login-center-container {
        padding: 0 0.5rem;
    }
    .login-card {
        padding: 1rem 0.5rem 1rem 0.5rem;
        max-width: 100%;
    }
    .login-logo img {
        height: 38px;
    }
    .login-logo span {
        font-size: 1.1rem;
    }
    .fw-normal, .login-title {
        font-size: 1.2rem;
    }
    .btn-login {
        font-size: 1rem;
        padding: 0.7rem 0;
    }
    .form-control {
        font-size: 1rem;
        padding: 0.6rem 0.8rem;
    }
}
    </style>
    @endpush

      <div class="login-bg"></div>
    <div class="login-center-container">
        <div class="login-card">
            <form method="POST" action="{{ route('login') }}" style="width:100%">
                @csrf

                <div class="login-logo">
                    <img src="{{ asset('images/bliss_logo.png') }}" alt="Logo">
                    <span>Bliss Coffee</span>
                </div>
                <hr class="login-separator">

                @if (session('status'))
                    <div class="alert alert-danger">{{ session('status') }}</div>
                @endif
                @if (session('error') == 'email-not-verified')
                    <div class="alert alert-warning mb-3" style="color:#b45309; background:#fef3c7; border-radius:8px; padding:10px;">
                        Email Anda belum diverifikasi. Silakan cek email Anda dan klik link verifikasi.
                    </div>
                @endif

                <h3 class="fw-normal login-title mb-3 pb-2 text-center" style="letter-spacing: 1px;">Login</h3>


                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" id="email" name="email"
                        class="form-control form-control-lg"
                        value="{{ old('email') }}" required autofocus autocomplete="username"/>
                    @error('email')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password"
                        class="form-control form-control-lg"
                        required autocomplete="current-password"/>
                    @error('password')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label" for="remember_me">Remember me</label>
                </div>

                <div class="mb-3">
                    <button class="btn btn-login btn-lg w-100 shadow-sm" type="submit">Login</button>
                </div>
            </form>
            <div class="login-footer">
                @if (Route::has('password.request'))
                    <a class="link-info" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
                @if (Route::has('register'))
                    <span>|</span>
                    <a href="{{ route('register') }}" class="link-info">Register here</a>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>