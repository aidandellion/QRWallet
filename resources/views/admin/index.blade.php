@extends('layouts.app')

@section('content')
    <div class="container">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="p-3 bg-white rounded" style="border: 1px solid #d1ccc8;">
                    <div class="text-muted small">Total Users</div>
                    <div class="fs-4 fw-bold" style="color: #000;">{{ $totalUsers }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-white rounded" style="border: 1px solid #d1ccc8;">
                    <div class="text-muted small">Total Active Users</div>
                    <div class="fs-4 fw-bold" style="color: #000;">{{ $totalActiveUsers }}</div>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.index') }}" class="d-flex gap-2 mb-3">
            <div class="input-group" style="max-width: 400px;">
                <span class="input-group-text bg-white" style="border-color: #d1ccc8;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control" placeholder="Search User..."
                    value="{{ request('search') }}" style="border-color: #d1ccc8;" onchange="this.form.submit()">
            </div>
        </form>

        <div class="bg-white rounded" style="border: 1px solid #d1ccc8;">
            @forelse ($users as $user)
                <div class="d-flex justify-content-between align-items-center p-3 {{ !$loop->last ? 'border-bottom' : '' }}"
                    style="border-color: #d1ccc8 !important;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle"
                            style="width: 10px; height: 10px; display: inline-block; background-color: {{ $user->status === 'active' ? '#28a745' : '#dc3545' }};">
                        </span>
                        <div>
                            <div class="fw-semibold" style="color: #000;">{{ $user->name }}</div>
                            <div class="text-muted small">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-link p-0" style="color: #855f4a;" data-bs-toggle="modal"
                            data-bs-target="#toggleStatusModal{{ $user->id }}">
                            <i class="bi bi-dash-circle fs-5"></i>
                        </button>
                        <button type="button" class="btn btn-link p-0" style="color: #855f4a;" data-bs-toggle="modal"
                            data-bs-target="#deleteModal{{ $user->id }}">
                            <i class="bi bi-trash fs-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Toggle Status Modal (Figure 6.14) -->
                <div class="modal fade" id="toggleStatusModal{{ $user->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center py-4">
                                <p class="mb-1">Are you sure you want to
                                    {{ $user->status === 'active' ? 'deactivate' : 'activate' }}?
                                </p>
                                <p class="fw-bold mb-3">{{ $user->name }}<br>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </p>
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn text-white" style="background-color: #855f4a;">
                                        {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Modal (Figure 6.15) -->
                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center py-4">
                                <p class="mb-1">Are you sure you want to delete?</p>
                                <p class="fw-bold mb-3">{{ $user->name }}<br>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </p>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn text-white"
                                        style="background-color: #855f4a;">Delete</button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-muted">
                    No users found.
                </div>
            @endforelse
        </div>
    </div>
@endsection