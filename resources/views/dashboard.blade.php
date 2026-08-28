@extends('layouts.app')

@section('content')
    <div class="container">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-start mb-4">
            <div class="row g-3 flex-grow-1 me-3">
                <div class="col-md-6">
                    <div class="p-3 bg-white rounded" style="border: 1px solid #d1ccc8;">
                        <div class="text-muted small">Total QR Codes</div>
                        <div class="fs-4 fw-bold" style="color: #000;">{{ $totalQrCodes }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-white rounded" style="border: 1px solid #d1ccc8;">
                        <div class="text-muted small">Total Payment Platform Used</div>
                        <div class="fs-4 fw-bold" style="color: #000;">{{ $totalPlatformsUsed }}</div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn d-flex align-items-center justify-content-center"
                style="background-color: #855f4a; color: #fff; width: 48px; height: 48px; border-radius: 8px;"
                data-bs-toggle="modal" data-bs-target="#addQrModal">
                <i class="bi bi-plus-lg fs-5"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2 mb-3">
            <div class="input-group" style="max-width: 400px;">
                <span class="input-group-text bg-white" style="border-color: #d1ccc8;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control" placeholder="Search QR Codes..."
                    value="{{ request('search') }}" style="border-color: #d1ccc8;">
            </div>

            <select name="platform" class="form-select" style="max-width: 200px; border-color: #d1ccc8;"
                onchange="this.form.submit()">
                <option value="">Select platform</option>
                @foreach ($platforms as $platform)
                    <option value="{{ $platform->id }}" {{ request('platform') == $platform->id ? 'selected' : '' }}>
                        {{ $platform->platform_name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn qrwallet-btn-secondary">Filter</button>
        </form>

        <div class="bg-white rounded" style="border: 1px solid #d1ccc8;">
            @forelse ($qrCodes as $qrCode)
                <div class="d-flex justify-content-between align-items-center p-3 {{ !$loop->last ? 'border-bottom' : '' }}"
                    style="border-color: #d1ccc8 !important;">
                    <div>
                        <div class="fw-semibold" style="color: #000;">{{ $qrCode->label }}</div>
                        <div class="text-muted small">{{ $qrCode->paymentPlatform->platform_name }}</div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-link p-0" style="color: #855f4a;" data-bs-toggle="modal"
                            data-bs-target="#viewQrModal" data-label="{{ $qrCode->label }}"
                            data-platform="{{ $qrCode->paymentPlatform->platform_name }}"
                            data-image="{{ asset('storage/' . $qrCode->qr_image) }}"
                            data-download="{{ route('qr-codes.download', $qrCode) }}">
                            <i class="bi bi-eye fs-5"></i>
                        </button>

                        <button type="button" class="btn btn-link p-0" style="color: #855f4a;" data-bs-toggle="modal"
                            data-bs-target="#editQrModal" data-id="{{ $qrCode->id }}" data-label="{{ $qrCode->label }}"
                            data-platform-id="{{ $qrCode->platform_id }}">
                            <i class="bi bi-pencil fs-5"></i>
                        </button>

                        <button type="button" class="btn btn-link p-0" style="color: #855f4a;" data-bs-toggle="modal"
                            data-bs-target="#deleteQrModal" data-id="{{ $qrCode->id }}" data-label="{{ $qrCode->label }}"
                            data-platform="{{ $qrCode->paymentPlatform->platform_name }}">
                            <i class="bi bi-trash fs-5"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-muted">
                    No QR codes yet. Click the + button to add your first one.
                </div>
            @endforelse
        </div>

    </div>
    @include('partials.qr-modals')
@endsection