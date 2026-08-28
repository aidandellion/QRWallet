@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 style="color: #000;">Profile Management</h5>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded p-4 mx-auto" style="border: 1px solid #d1ccc8; max-width: 500px;">
            <a href="{{ route('dashboard') }}" class="text-decoration-none d-inline-block mb-4" style="color: #855f4a;">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <div class="d-flex align-items-start gap-2 mb-3">
                <i class="bi bi-person-circle mt-1" style="color: #855f4a;"></i>
                <div>
                    <div class="text-muted small">Name</div>
                    <div style="color: #000;">{{ $user->name }}</div>
                </div>
            </div>

            <div class="d-flex align-items-start gap-2 mb-3">
                <i class="bi bi-envelope mt-1" style="color: #855f4a;"></i>
                <div>
                    <div class="text-muted small">Email</div>
                    <div style="color: #000;">{{ $user->email }}</div>
                </div>
            </div>

            <div class="d-flex align-items-start gap-2 mb-4">
                <i class="bi bi-lock mt-1" style="color: #855f4a;"></i>
                <div>
                    <div class="text-muted small">Password</div>
                    <div style="color: #000;">••••••••</div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-link p-0 text-danger" data-bs-toggle="modal"
                    data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </button>

                <a href="{{ route('profile.edit') }}" class="btn" style="background-color: #855f4a; color: #fff;">
                    Edit
                </a>
            </div>
        </div>
    </div>
@endsection