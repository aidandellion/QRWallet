@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 style="color: #000;">Profile Management</h5>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded p-4 mx-auto" style="border: 1px solid #d1ccc8; max-width: 500px;">
            <a href="{{ route('profile.show') }}" class="text-decoration-none d-inline-block mb-4" style="color: #855f4a;">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small text-muted">Name</label>
                    <input type="text" name="name" class="form-control" style="border-color: #d1ccc8;"
                        value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted">Email</label>
                    <input type="email" name="email" class="form-control" style="border-color: #d1ccc8;"
                        value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted">Password</label>
                    <input type="password" name="password" class="form-control" style="border-color: #d1ccc8;"
                        placeholder="Leave blank to keep current password">
                </div>

                <div class="mb-4">
                    <label class="form-label small text-muted">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" style="border-color: #d1ccc8;">
                </div>

                <button type="submit" class="btn w-100" style="background-color: #855f4a; color: #fff;">
                    Save
                </button>
            </form>
        </div>
    </div>
@endsection