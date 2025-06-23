{{-- filepath: /home/acra/bliss/resources/views/auth/reset-password.blade.php --}}
<x-guest-layout>
    <style>
        body {
            background: linear-gradient(180deg, #003200 0%, #004d26 70%);
        }
        .reset-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: url('{{ asset('images/coffee_bg.jpg') }}') center center/cover no-repeat;
            opacity: 0.18;
        }
        .reset-center-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }
        .reset-card {
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
        .reset-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 0.3rem;
        }
        .reset-logo img {
            height: 54px;
            margin-bottom: 0.2rem;
        }
        .reset-logo span {
            font-size: 1.7rem;
            font-weight: bold;
            color: #fff;
            letter-spacing: 1px;
            margin-bottom: 0.2rem;
        }
        .reset-separator {
            width: 100%;
            border: none;
            border-top: 2px solid #a3a3a3;
            margin: 0 0 1.1rem 0;
        }
        .reset-title {
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
        .reset-btn {
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
        .reset-btn:hover {
            background: #7B5130;
        }
        @media (max-width: 600px) {
            .reset-card { padding: 1.2rem 0.7rem 1rem 0.7rem; max-width: 100%; }
            .reset-logo img { height: 38px; }
            .reset-logo span { font-size: 1.1rem; }
            .reset-title { font-size: 1.1rem; }
        }
    </style>

    <div class="reset-bg"></div>
    <div class="reset-center-container">
        <div class="reset-card">
            <div class="reset-logo">
                <img src="{{ asset('images/bliss_logo.png') }}" alt="Logo">
                <span>Bliss Coffee</span>
            </div>
            <hr class="reset-separator">
            <div class="reset-title">Reset Password</div>

            @if ($errors->any())
                <div style="color:#fbbf24; margin-bottom:12px;">
                    <ul style="padding-left: 0; list-style: none;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" style="width:100%">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                    class="form-control" placeholder="Email">
                @error('email')
                    <div class="text-danger" style="color:#fbbf24;">{{ $message }}</div>
                @enderror

                <input id="password" type="password" name="password" required
                    class="form-control" placeholder="Password Baru">
                @error('password')
                    <div class="text-danger" style="color:#fbbf24;">{{ $message }}</div>
                @enderror

                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="form-control" placeholder="Konfirmasi Password">

                <button type="submit" class="reset-btn">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>