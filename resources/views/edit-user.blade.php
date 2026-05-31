@extends('layouts.main')

@section('content')

<nav id="sidebar" class="sidebar d-flex flex-column">
    <div>
        <div class="sidebar-brand">
            <img src="{{ asset('ASSETS/frog.png') }}" style="width:32px; height:32px;" alt="Logo">
            <span>Notes Manager</span>
        </div>

        <div class="mt-3">
            <a href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/user"><i class="bi bi-person"></i> User</a>
            <a href="/notes"><i class="bi bi-journal-text"></i> My Notes</a>
        </div>
    </div>

    <div class="mt-auto mb-3">
        <a href="/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</nav>

<div class="main">
    <header class="topbar">
        <div class="d-flex align-items-center">
            <div class="toggle-btn" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </div>
            <nav class="breadcrumb-nav ms-3">
                <a href="/user">User Management</a>
                <span class="sep">›</span>
                <span class="text-dark fw-semibold">Edit User</span>
            </nav>
        </div>
        <div>
            <a href="/profile" class="d-flex align-items-center text-decoration-none">
                <img src="{{ auth()->user() && auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : asset('ASSETS/blank-pfp.png') }}" alt="Profile" 
                     style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
            </a>
        </div>
    </header>

    <main class="main-wrapper">
        <div class="sticky-note">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold">Edit User</h4>
            </div>

            <form action="/user/{{ $user->id }}/edit" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ $user->name }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ $user->email }}" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control @error('gender') is-invalid @enderror" 
                            id="gender" name="gender" required>
                        <option value="">-- Select Gender --</option>
                        <option value="Male" @selected($user->gender === 'Male')>Male</option>
                        <option value="Female" @selected($user->gender === 'Female')>Female</option>
                    </select>
                    @error('gender')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/user" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

@endsection

@push('scripts')
<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('show');
}
</script>
@endpush
