{{-- filepath: /home/acra/bliss/resources/views/auth/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
        /* Green gradient background */
        background: linear-gradient(180deg, #003200 0%, #004d26 70%);
        min-height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
    }
        .verify-container {
            max-width: 380px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
            text-align: center;
        }
        .verify-logo {
            height: 60px;
            margin-bottom: 16px;
        }
        .verify-title {
            font-size: 1.6rem;
            font-weight: bold;
            color: #222;
            margin-bottom: 12px;
        }
        .verify-desc {
            color: #333;
            font-size: 1rem;
            margin-bottom: 18px;
        }
        .verify-success {
            color: #16a34a;
            font-size: 0.98rem;
            margin-bottom: 18px;
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
    </style>
</head>
<body>
    <div class="verify-container">
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
</body>
</html>