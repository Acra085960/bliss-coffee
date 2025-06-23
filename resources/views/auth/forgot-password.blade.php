{{-- filepath: /home/acra/bliss/resources/views/auth/forgot-password.blade.php --}}
<x-guest-layout>
    <style>
        body {
            background: linear-gradient(180deg, #003200 0%, #004d26 70%);
        }
        .forgot-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: url('{{ asset('images/coffee_bg.jpg') }}') center center/cover no-repeat;
            opacity: 0.18;
        }
        .forgot-center-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }
        .forgot-card {
            background: #223322;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            padding: 2.2rem 2rem 1.2rem 2rem;
            width: 100%;
            max-width: 370px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .forgot-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 0.3rem;
        }
        .forgot-logo img {
            height: 54px;
            margin-bottom: 0.2rem;
        }
        .forgot-logo span {
            font-size: 1.7rem;
            font-weight: bold;
            color: #fff;
            letter-spacing: 1px;
            margin-bottom: 0.2rem;
        }
        .forgot-separator {
            width: 100%;
            border: none;
            border-top: 2px solid #a3a3a3;
            margin: 0 0 1.1rem 0;
        }
        .forgot-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 18px;
            text-align: center;
            letter-spacing: 1px;
        }
        .form-label {
            color: #fff;
        }
        .form-control {
            background: #f5f5dc;
            border-radius: 8px;
            color: #003200;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 12px;
        }
        .form-control:focus {
            border-color: #A9744F;
            box-shadow: 0 0 0 0.2rem rgba(169, 116, 79, 0.15);
        }
        .forgot-btn {
            width: 100%;
            padding: 10px 0;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            background: #A9744F;
            color: #fff;
            margin-top: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .forgot-btn:hover {
            background: #7B5130;
        }
        .forgot-footer {
            width: 100%;
            margin-top: 1.2rem;
            text-align: center;
        }
        .forgot-footer .link-info {
            color: #c7b299;
            text-decoration: none;
        }
        .forgot-footer .link-info:hover {
            color: #e0cfa9;
            text-decoration: underline;
        }
        @media (max-width: 600px) {
    .forgot-card { 
        padding: 1.2rem 0.7rem 1rem 0.7rem; 
        max-width: 100%; 
    }
    .forgot-logo img { 
        height: 38px; 
    }
    .forgot-logo span { 
        font-size: 1.1rem; 
    }
    .forgot-title { 
        font-size: 1.1rem; 
    }
    .form-control {
        font-size: 1rem;
        padding: 0.6rem 0.8rem;
    }
    .forgot-btn {
        font-size: 1rem;
        padding: 0.7rem 0;
    }
    .forgot-footer {
        margin-top: 1rem;
    }
}
    </style>

    <div class="forgot-bg"></div>
    <div class="forgot-center-container">
        <div class="forgot-card">
            <div class="forgot-logo">
                <img src="{{ asset('images/bliss_logo.png') }}" alt="Logo">
                <span>Bliss Coffee</span>
            </div>
            <hr class="forgot-separator">
            <div class="forgot-title">Forgot Password?</div>
            <p class="text-center" style="color:#fff; margin-bottom:18px;">
                Enter your email address and we'll send you a password reset link.
            </p>

            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" style="width:100%">
                @csrf

                <input type="email" id="email" name="email"
                    class="form-control form-control-lg"
                    value="{{ old('email') }}" required autofocus
                    placeholder="Email address"/>
                @error('email')
                    <div class="text-danger mt-1 small">{{ $message }}</div>
                @enderror

                <button class="forgot-btn" type="submit">
                    Send Reset Link
                </button>
            </form>
            <div class="forgot-footer">
                <a href="{{ route('login') }}" class="link-info">
                    ← Back to Login
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>