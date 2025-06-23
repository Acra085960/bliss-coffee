{{-- filepath: /home/acra/bliss/resources/views/auth/verify-email.blade.php --}}
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
            height: 54px;
            margin-bottom: 0.2rem;
        }
        .verify-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 12px;
            letter-spacing: 1px;
            text-align: center;
        }
        .verify-desc {
            color: #fff;
            font-size: 1rem;
            margin-bottom: 18px;
            text-align: center;
        }
        .verify-success {
            color: #16a34a;
            font-size: 0.98rem;
            margin-bottom: 18px;
            text-align: center;
        }
        .verify-btn, .verify-btn-secondary {
            width: 100%;
            padding: 10px 0;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .verify-btn {
            background: #A9744F;
            color: #fff;
        }
        .verify-btn:hover {
            background: #7B5130;
        }
        .verify-btn-secondary {
            background: #e5e7eb;
            color: #222;
        }
        .verify-btn-secondary:hover {
            background: #d1d5db;
        }
        @media (max-width: 600px) {
            .verify-card { padding: 1.2rem 0.7rem 1rem 0.7rem; max-width: 100%; }
            .verify-logo { height: 38px; }
            .verify-title { font-size: 1.1rem; }
        }
    </style>

    <div class="verify-bg"></div>
    <div class="verify-center-container">
        <div class="verify-card">
            <img src="{{ asset('images/bliss_logo.png') }}" alt="Logo" class="verify-logo">
            <div class="verify-title">Verifikasi Email</div>
            <div class="verify-desc">
                Terima kasih telah mendaftar!<br>
                Sebelum mulai, silakan verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan.<br>
                Jika Anda belum menerima email, kami dapat mengirim ulang untuk Anda.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="verify-success">
                    Link verifikasi baru telah dikirim ke email Anda.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="verify-btn">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="verify-btn-secondary">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>