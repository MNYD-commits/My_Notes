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
                <span class="text-dark fw-semibold">User Management</span>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold">User Management</h4>
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus-lg me-1"></i> Add User
                </button>
            </div>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->gender }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info rounded-pill px-3"
                                        onclick="openEditModal({{ $user->id }},'{{ $user->name }}','{{ $user->email }}','{{ strtolower($user->gender) }}')">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger rounded-pill px-3"
                                        onclick="openDeleteModal({{ $user->id }})">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">

      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">Add User</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="addForm" method="POST" action="/user/store">
        @csrf
        <div class="modal-body pt-0">

          {{-- <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text" class="form-control custom-input" name="name" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control custom-input" name="email" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Gender</label>
            <select class="form-control custom-input" name="gender" required>
              <option value="">-- Select Gender --</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" class="form-control custom-input" name="password" required>
          </div>
        </div> --}}

        <div class="mb-3">
          <label>Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <div class="mb-3">
          <label>Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="d-block">Gender</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="Male" required>
            <label class="form-check-label">Male</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="Female" required>
            <label class="form-check-label">Female</label>
          </div>
        </div>
      </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal" onclick="resetAddForm()">Cancel</button>
          <button type="button" class="btn btn-success rounded-pill px-4" onclick="submitAddForm()">Save</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">

      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">Edit User</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="editForm" method="POST">
        @csrf
        <div class="modal-body pt-0">
          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input id="editName" type="text" class="form-control custom-input" name="name" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input id="editEmail" type="email" class="form-control custom-input" name="email" required>
          </div>

          <div>
            <label class="form-label fw-semibold">Gender</label>
            <select id="editGender" class="form-control custom-input" name="gender" required>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary rounded-pill px-4" onclick="submitEditForm()">Save Changes</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal">

      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-danger">Delete User</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body text-center pt-0">
          <p class="mb-1 fw-semibold">Are you sure you want to delete this user?</p>
          <small class="text-muted">This action cannot be undone.</small>
        </div>

        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger rounded-pill px-4" onclick="submitDeleteForm()">Delete</button>
        </div>
      </form>

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('show');
}

function openEditModal(id, name, email, gender) {
  // Set modal values
  document.getElementById('editForm').action = '/user/' + id + '/edit';
  document.getElementById('editName').value = name;
  document.getElementById('editEmail').value = email;
  document.getElementById('editGender').value = gender;
  
  // Show modal
  const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
  editModal.show();
}

function openDeleteModal(id) {
  document.getElementById('deleteForm').action = '/user/' + id + '/delete';
  const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
  deleteModal.show();
}

function submitEditForm() {
  const form = document.getElementById('editForm');
  form.submit();
}

function submitDeleteForm() {
  const form = document.getElementById('deleteForm');
  form.submit();
}

function resetAddForm() {
  document.getElementById('addForm').reset();
}

function validateAddForm() {
  const name = document.querySelector('#addForm input[name="name"]').value.trim();
  const email = document.querySelector('#addForm input[name="email"]').value.trim();
  const gender = document.querySelector('#addForm input[name="gender"]:checked')?.value || '';
  const password = document.querySelector('#addForm input[name="password"]').value;
  const confirmPassword = document.querySelector('#addForm input[name="password_confirmation"]').value;

  if (!name) {
    alert('Please enter full name');
    return false;
  }
  if (!email) {
    alert('Please enter email');
    return false;
  }
  if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
    alert('Please enter a valid email');
    return false;
  }
  if (!gender) {
    alert('Please select a gender');
    return false;
  }
  if (!password) {
    alert('Please enter password');
    return false;
  }
  if (password.length < 6) {
    alert('Password must be at least 6 characters');
    return false;
  }
  if (!confirmPassword) {
    alert('Please confirm your password');
    return false;
  }
  if (password !== confirmPassword) {
    alert('Passwords do not match');
    return false;
  }
  return true;
}

function submitAddForm() {
  if (validateAddForm()) {
    document.getElementById('addForm').submit();
  }
}
</script>
@endpush
