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
                <a href="#">Portal</a>
                <span class="sep">›</span>
                <span class="text-dark fw-semibold">My Profile</span>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end">
                <div class="small fw-semibold">{{ $user->name }}</div>
                <div class="small text-muted">{{ $user->email }}</div>
            </div>
            <a href="/profile" class="d-flex align-items-center text-decoration-none">
                <img src="{{ auth()->user()->profile_picture_base64 ?? (auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : asset('ASSETS/blank-pfp.png')) }}" alt="Profile" 
                     style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
            </a>
        </div>
    </header>

    <br>

    <main class="main-wrapper">

        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card custom-modal border-0 p-4">

                        <h3 class="text-center fw-bold mb-4">Edit Profile</h3>

                        <!-- PROFILE FORM -->
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- PROFILE IMAGE -->
                            <div class="text-center mb-4">
                                <img src="{{ $user->profile_picture_base64 ?? ($user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('ASSETS/blank-pfp.png')) }}" 
                                     class="rounded-circle mb-3"
                                     style="width:100px; height:100px; object-fit:cover;">

                                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                    <input type="file" class="form-control custom-input" name="profile_image" style="max-width:250px;" accept="image/*">
                                </div>
                                <small class="text-muted">Upload a new profile image (optional)</small>
                            </div>

                            <hr>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Full Name</label>
                                    <input type="text"
                                        class="form-control custom-input"
                                        name="name"
                                        value="{{ $user->name }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email"
                                        class="form-control custom-input"
                                        value="{{ $user->email }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Gender</label>
                                    <select class="form-select custom-input" name="gender">
                                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>  
                                </div>

                            </div>

                            <hr class="my-4">

                            <!-- PASSWORD -->
                            <h5 class="fw-bold mb-3">Change Password</h5>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Current Password</label>
                                    <input type="password"
                                        class="form-control custom-input"
                                        name="current_password">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">New Password</label>
                                    <input type="password"
                                        class="form-control custom-input"
                                        name="new_password">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Confirm New Password</label>
                                    <input type="password"
                                        class="form-control custom-input"
                                        name="new_password_confirmation">
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit"
                                    class="btn btn-success rounded-pill px-4">
                                    Save Changes
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>

    </main>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
.main-wrapper {
  padding: 30px;
}

.card {
  background: #dff5e1 !important;
  border-radius: 16px !important;
  box-shadow: 10px 8px 0 #497151 !important;
}

.form-select.custom-input {
  border-radius: 12px;
  border: 1px solid #a9c7b1;
  padding: 10px;
  transition: 0.2s;
}

.form-select.custom-input:focus {
  border-color: #497151;
  box-shadow: 0 0 0 2px rgba(73,113,81,0.2);
}

.btn-success {
  background-color: #497151 !important;
  border: none;
  font-weight: 600;
}

.btn-success:hover {
  background-color: #76c787 !important;
}

@media (max-width: 768px) {
  .main-wrapper {
    padding: 15px;
  }

  .card {
    padding: 20px !important;
    box-shadow: 8px 6px 0 #497151 !important;
  }

  .row.g-3 {
    row-gap: 1rem;
  }
}

@media (max-width: 576px) {
  .main-wrapper {
    padding: 10px;
  }

  .card {
    padding: 15px !important;
    box-shadow: 5px 4px 0 #497151 !important;
  }

  h3 {
    font-size: 1.5rem !important;
  }

  .col-md-6 {
    flex: 0 0 100% !important;
  }

  .form-control,
  .form-select {
    font-size: 0.9rem;
  }
}
</style>
@endpush

@push('scripts')
<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('show');
}

function editProfile() {
  alert('Edit profile functionality coming soon!');
}

function changePassword() {
  alert('Change password functionality coming soon!');
}
</script>
@endpush
