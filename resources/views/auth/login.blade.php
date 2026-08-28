@extends('layouts.auth')

@section('content')
    <div class="qrwallet-login-card">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="QRWallet" class="qrwallet-logo">
            <p class="qrwallet-subtitle">Sign in to your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="qrwallet-label">Email</label>
                <input id="email" type="email" class="qrwallet-input @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="qrwallet-label">Password</label>
                <input id="password" type="password" class="qrwallet-input @error('password') is-invalid @enderror"
                    name="password" required>
                @error('password')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn qrwallet-btn-primary">
                Sign in
            </button>

            <p class="qrwallet-footer-text mt-3">
                Don't have an account? <a href="{{ route('register') }}" class="qrwallet-link">Register</a>
            </p>
        </form>
    </div>
@endsection