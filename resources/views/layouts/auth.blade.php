<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>QRWallet</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background-color: #ffffff;
            margin: 0;
        }

        .qrwallet-auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .qrwallet-auth-card {
            width: 100%;
        }

        .qrwallet-logo {
            max-width: 160px;
            margin-bottom: 0.5rem;
        }

        .qrwallet-subtitle {
            color: #000000;
            font-size: 1.05rem;
            margin-top: 0.75rem;
            margin-bottom: 2rem;
        }

        .qrwallet-label {
            color: #000000;
            font-size: 0.9rem;
            margin-bottom: 0.35rem;
            display: block;
        }

        .qrwallet-input {
            background-color: #f6f4f1;
            border: 1px solid #d1ccc8;
            border-radius: 4px;
            padding: 0.75rem 0.9rem;
            width: 100%;
            box-sizing: border-box;
            font-size: 1rem;
            display: block;
        }

        .qrwallet-input:focus {
            border-color: #855f4a;
            box-shadow: 0 0 0 0.2rem rgba(133, 95, 74, 0.15);
            outline: none;
        }

        .qrwallet-btn-primary {
            background-color: #855f4a;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 0.75rem;
            font-weight: 500;
            font-size: 1rem;
            width: 100%;
        }

        .qrwallet-btn-primary:hover {
            background-color: #6e4d3c;
            color: #ffffff;
        }

        .qrwallet-footer-text {
            color: #000000;
            font-size: 0.95rem;
            text-align: center;
        }

        .qrwallet-link {
            color: #855f4a;
            font-weight: 500;
            text-decoration: none;
        }

        .qrwallet-link:hover {
            text-decoration: underline;
        }

        .qrwallet-login-card {
            max-width: 480px;
            width: 100%;
            margin: 0 auto;
        }

.qrwallet-register-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    align-items: center;
    width: 100%;
    max-width: 100%;
}

        .qrwallet-register-logo-col {
            display: flex;
            justify-content: center;
            align-items: center;
        }


        .qrwallet-register-logo-col .qrwallet-logo {
            max-width: 220px;
        }

        .qrwallet-register-form-col {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            max-width: 600px;
            min-width: 480px;
            margin: 0 auto;
            width: 100%;
        }

        .qrwallet-register-title {
            color: #000000;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .qrwallet-register-subtitle {
            color: #000000;
            font-size: 0.85rem;
            font-style: italic;
            margin-bottom: 1.75rem;
        }
    </style>
</head>

<body>
    <div class="qrwallet-auth-wrapper">
        <div class="qrwallet-auth-card">
            @yield('content')
        </div>
    </div>
</body>

</html>