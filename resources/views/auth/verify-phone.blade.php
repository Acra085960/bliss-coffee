{{-- filepath: /home/acra/bliss/resources/views/auth/verify-phone.blade.php --}}
<x-guest-layout>
    <style>
        body {
            background: linear-gradient(180deg, #003200 0%, #004d26 70%);
        }
        .verify-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: url('{{ asset('images/coffee_bg.jpg') }}') center center/cover no-repeat;
            opacity: 0.18;
        }
        .verify-center-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }
        .verify-card {
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
        .verify-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 0.3rem;
        }
        .verify-logo img {
            height: 54px;
            margin-bottom: 0.2rem;
        }
        .verify-logo span {
            font-size: 1.7rem;
            font-weight: bold;
            color: #fff;
            letter-spacing: 1px;
            margin-bottom: 0.2rem;
        }
        .verify-separator {
            width: 100%;
            border: none;
            border-top: 2px solid #a3a3a3;
            margin: 0 0 1.1rem 0;
        }
        .verify-title {
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
        .verify-btn {
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
        .verify-btn:hover {
            background: #7B5130;
        }
        .resend-btn {
            background: transparent;
            color: #c7b299;
            border: 1px solid #c7b299;
            margin-top: 10px;
        }
        .resend-btn:hover {
            background: #c7b299;
            color: #223322;
        }
        .verify-footer {
            width: 100%;
            margin-top: 1rem;
            text-align: center;
        }
        .verify-footer .link-info {
            color: #c7b299;
            text-decoration: none;
        }
        .verify-footer .link-info:hover {
            color: #e0cfa9;
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .verify-card { padding: 1.2rem 0.7rem 1rem 0.7rem; max-width: 100%; }
            .verify-logo img { height: 38px; }
            .verify-logo span { font-size: 1.1rem; }
            .verify-title { font-size: 1.1rem; }
        }
    </style>

    <div class="verify-bg"></div>
    <div class="verify-center-container">
        <div class="verify-card">
            <div class="verify-logo">
                <img src="{{ asset('images/bliss_logo.png') }}" alt="Logo">
                <span>Bliss Coffee</span>
            </div>
            <hr class="verify-separator">
            <div class="verify-title">WhatsApp Verification</div>
            <p class="text-center" style="color:#fff; margin-bottom:18px;">
                @if(session('demo_mode'))
                    Demo mode: Enter any 6-digit code to continue testing.
                @else
                    We've sent a verification code to your WhatsApp. Please enter the code below.
                @endif
            </p>

            @if (session('success'))
                <div style="color:#28a745; margin-bottom:12px; text-align:center;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div style="color:#17a2b8; margin-bottom:12px; text-align:center;">
                    {{ session('info') }}
                </div>
            @endif

            @if (session('error'))
                <div style="color:#dc3545; margin-bottom:12px; text-align:center;">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="color:#dc3545; margin-bottom:12px;">
                    <ul style="padding-left: 0; list-style: none;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('phone.verification.verify') }}" style="width:100%">
                @csrf

                <input type="text" id="verification_code" name="verification_code"
                    class="form-control" placeholder="Enter 6-digit code"
                    maxlength="6" required autofocus>

                <button type="submit" class="verify-btn">
                    Verify Code
                </button>
            </form>

            <form method="POST" action="{{ route('phone.verification.resend') }}" style="width:100%">
                @csrf
                <button type="submit" class="verify-btn resend-btn">
                    Resend Code
                </button>
            </form>

            <div class="verify-footer">
                <a href="{{ route('login') }}" class="link-info">
                    ← Back to Login
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>