{{-- filepath: /home/acra/bliss/resources/views/auth/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: linear-gradient(180deg, #003200 0%, #004d26 70%);
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .reset-container {
            max-width: 380px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
            text-align: center;
        }
        .reset-title {
            font-size: 1.6rem;
            font-weight: bold;
            color: #222;
            margin-bottom: 12px;
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
            margin-top: 16px;
            cursor: pointer;
        }
        .reset-btn:hover {
            background: #7B5130;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-title">Reset Password</div>

        @if ($errors->any())
    <div style="color:#b91c1c; margin-bottom:12px;">
        <ul style="padding-left: 0; list-style: none;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

       <form method="POST" action="{{ route('password.store') }}">
    @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                placeholder="Email" style="width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;margin-bottom:12px;">
            @error('email')
                <div class="text-danger" style="color:#b91c1c;">{{ $message }}</div>
            @enderror

            <input id="password" type="password" name="password" required
                placeholder="Password Baru" style="width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;margin-bottom:12px;">
            @error('password')
                <div class="text-danger" style="color:#b91c1c;">{{ $message }}</div>
            @enderror

            <input id="password_confirmation" type="password" name="password_confirmation" required
                placeholder="Konfirmasi Password" style="width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;margin-bottom:12px;">

            <button type="submit" class="reset-btn">
                Reset Password
            </button>
        </form>
    </div>
</body>
</html>