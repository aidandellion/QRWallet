@extends('layouts.auth')

@section('content')
    <div class="qrwallet-register-grid">
        <div class="qrwallet-register-logo-col">
            <img src="{{ asset('images/logo.png') }}" alt="QRWallet" class="qrwallet-logo">
        </div>

        <div class="qrwallet-register-form-col">
            <h2 class="qrwallet-register-title text-center">Create account</h2>
            <p class="qrwallet-register-subtitle text-center">Start organizing your DuitNow QR Codes today</p>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="qrwallet-label">Name</label>
                    <input id="name" type="text" class="qrwallet-input @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="qrwallet-label">Email</label>
                    <input id="email" type="email" class="qrwallet-input @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="qrwallet-label">Password</label>
                    <input id="password" type="password" class="qrwallet-input @error('password') is-invalid @enderror"
                        name="password" required>
                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="qrwallet-label">Confirm Password</label>
                    <input id="password-confirm" type="password" class="qrwallet-input" name="password_confirmation"
                        required>
                </div>

                <button type="submit" class="btn qrwallet-btn-primary">
                    Register
                </button>

                <p class="qrwallet-footer-text mt-3">
                    Already have an account? <a href="{{ route('login') }}" class="qrwallet-link">Login</a>
                </p>
            </form>
        </div>
    </div>
@endsection