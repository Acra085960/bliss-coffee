{{-- filepath: /home/acra/bliss/resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <div class="register-bg"></div>
    <style>
        body {
    background: linear-gradient(180deg, #003200 0%, #004d26 70%);
}
.register-bg {
    position: fixed;
    inset: 0;
    z-index: 0;
    background: url('{{ asset('images/coffee_bg.jpg') }}') center center/cover no-repeat;
    opacity: 0.18;
}
.register-center-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
}
.register-card {
    background: #223322;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    padding: 2.2rem 2rem 1.2rem 2rem;
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.register-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 0.3rem;
}
.register-logo img {
    height: 54px;
    margin-bottom: 0.2rem;
}
.register-logo span {
    font-size: 1.7rem;
    font-weight: bold;
    color: #fff;
    letter-spacing: 1px;
    margin-bottom: 0.2rem;
}
.register-separator {
    width: 100%;
    border: none;
    border-top: 2px solid #a3a3a3;
    margin: 0 0 1.1rem 0;
}
.form-label {
    color: #fff;
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
.btn-register {
    background: #A9744F;
    color: #fff;
    border-radius: 8px;
    font-weight: bold;
    transition: background 0.2s;
}
.btn-register:hover {
    background: #7B5130;
}
.register-footer {
    width: 100%;
    margin-top: 1.2rem;
    text-align: center;
}
.register-footer .link-info {
    color: #c7b299;
    text-decoration: none;
}
.register-footer .link-info:hover {
    color: #e0cfa9;
    text-decoration: underline;
}
@media (max-width: 600px) {
    .register-card { padding: 1.2rem 0.7rem 1rem 0.7rem; max-width: 100%; }
    .register-logo img { height: 38px; }
    .register-logo span { font-size: 1.1rem; }
    .fw-normal { font-size: 1.2rem; }
    .btn-register { font-size: 1rem; padding: 0.7rem 0; }
    .form-control { font-size: 1rem; padding: 0.6rem 0.8rem; }
}
    </style>

    <div class="register-center-container">
        <div class="register-card">
            <div class="register-logo">
                <img src="{{ asset('images/bliss_logo.png') }}" alt="Logo">
                <span>Bliss Coffee</span>
            </div>
            <hr class="register-separator">
            <form method="POST" action="{{ route('register') }}" style="width:100%">
                @csrf

                <h3 class="fw-normal mb-3 pb-3 text-center" style="letter-spacing: 1px; color:#fff;">Register</h3>

                <!-- Name -->
                <div class="form-outline mb-4">
                    <input type="text" id="name" name="name"
                        class="form-control form-control-lg"
                        value="{{ old('name') }}" required autofocus autocomplete="name"/>
                    <label class="form-label" for="name">Full Name</label>
                    @error('name')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-outline mb-4">
                    <input type="email" id="email" name="email"
                        class="form-control form-control-lg"
                        value="{{ old('email') }}" required autocomplete="username"/>
                    <label class="form-label" for="email">Email address</label>
                    @error('email')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div class="form-outline mb-4">
                    <input type="tel" id="phone" name="phone"
                        class="form-control form-control-lg"
                        value="{{ old('phone') }}" required 
                        placeholder="08123456789"/>
                    <label class="form-label" for="phone">Phone Number (WhatsApp)</label>
                    @error('phone')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Verification Method -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="verification_method">Choose Verification Method</label>
                    <select id="verification_method" name="verification_method" 
                            class="form-control form-control-lg" required>
                        <option value="">Select verification method</option>
                        <option value="email" {{ old('verification_method') == 'email' ? 'selected' : '' }}>
                            Email Verification Only
                        </option>
                        <option value="whatsapp" {{ old('verification_method') == 'whatsapp' ? 'selected' : '' }}>
                            WhatsApp Verification Only
                        </option>
                    </select>
                    <small class="text-white-50 mt-1">
                        <strong>Email:</strong> Verify via email link only<br>
                        <strong>WhatsApp:</strong> Verify via WhatsApp code only
                    </small>
                    @error('verification_method')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-outline mb-4">
                    <input type="password" id="password" name="password"
                        class="form-control form-control-lg"
                        required autocomplete="new-password"/>
                    <label class="form-label" for="password">Password</label>
                    @error('password')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-outline mb-4">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control form-control-lg"
                        required autocomplete="new-password"/>
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    @error('password_confirmation')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="pt-1 mb-4">
                    <button class="btn btn-register btn-lg w-100 shadow-sm" type="submit">Register</button>
                </div>
            </form>
            <div class="register-footer">
                <p class="text-center mb-0">Already have an account?
                    <a href="{{ route('login') }}" class="link-info">Login here</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>