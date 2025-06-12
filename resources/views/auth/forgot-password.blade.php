{{-- filepath: /home/acra/bliss/resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: linear-gradient(180deg, #003200 0%, #004d26 70%);
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .forgot-container {
            max-width: 380px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
            text-align: center;
        }
        .forgot-title {
            font-size: 1.6rem;
            font-weight: bold;
            color: #222;
            margin-bottom: 12px;
        }
        .forgot-desc {
            color: #333;
            font-size: 1rem;
            margin-bottom: 18px;
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
            margin-top: 16px;
            cursor: pointer;
        }
        .forgot-btn:hover {
            background: #7B5130;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-title">Lupa Password</div>
        <div class="forgot-desc">
            Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="Email" style="width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;margin-bottom:12px;">
            @error('email')
                <div class="text-danger" style="color:#b91c1c;">{{ $message }}</div>
            @enderror

            <button type="submit" class="forgot-btn">
                Kirim Link Reset Password
            </button>
        </form>
    </div>
</body>
</html>