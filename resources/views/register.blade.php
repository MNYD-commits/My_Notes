@extends('layouts.main')

@section('content')

<div class="container vh-100 d-flex justify-content-center align-items-center">
  <div class="sticky-note">

    <h3>Create Account</h3>

    <form action="/register" method="POST">
      @csrf

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
        <input type="password" name="confirmPassword" class="form-control" required>
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

      <div class="d-grid">
        <button type="submit" class="btn btn-custom btn-lg">Sign Up</button>
      </div>

      <div class="text-center mt-3">
        <small>
          Already have an account?
          <a href="{{ url('/login') }}" class="small-link fw-bold">Login</a>
        </small>
      </div>

    </form>

    <!-- Frog -->
    <img src="{{ asset('ASSETS/frog.png') }}" class="frog" alt="frog">

  </div>
</div>

@endsection

@push('styles')
<style>
body {
  background: #ffffff;
  font-family: 'Segoe UI', sans-serif;
}

.sticky-note {
  position: relative;
  background: #BEE5B0;
  padding: 35px;
  width: 100%;
  max-width: 450px;
  border-radius: 15px;
  box-shadow: 8px 8px 0 #497151;
}

/* Fold corner */
.sticky-note::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  border-width: 0 0 40px 40px;
  border-style: solid;
  border-color: transparent transparent #497151 transparent;
}

/* Tape */
.sticky-note::after {
  content: "";
  position: absolute;
  top: -15px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 25px;
  background: #f5e6c8;
  border-radius: 5px;
}

h3 {
  text-align: center;
  color: #3c7a4a;
  margin-bottom: 20px;
}

.form-control {
  background: #f4fff6;
  border: 1px solid #b6dcb9;
  border-radius: 10px;
}

.form-control:focus {
  border-color: #7acb8a;
  box-shadow: none;
}

.btn-custom {
  background: #8ed39c !important;
  border: none !important;
  border-radius: 25px !important;
  color: black !important;
  font-weight: 600 !important;
}

.btn-custom:hover {
  background: #76c787 !important;
  color: black !important;
}

.alert {
  background: #ffe5e5;
  border: none;
  color: #c94b4b;
  border-radius: 10px;
}

.small-link {
  font-size: 13px;
  color: #4c8c5a;
}

.small-link:hover {
  text-decoration: underline;
}

/* Frog */
.frog {
  position: absolute;
  bottom: -10px;
  right: -10px;
  width: 70px;
}
</style>
@endpush