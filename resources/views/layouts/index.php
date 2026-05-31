<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email    = mysqli_real_escape_string($conn, $_POST['email']);
  $password = $_POST['password'];

  $sql = "SELECT * FROM tbl_users WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user['name'];
      $_SESSION['email'] = $user['email'];
      header("Location: dashboard.php");
      exit();
    }
  }

  header("Location: login.php?status=error");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #ffffff;
      font-family: 'Segoe UI', sans-serif;
    }

    .sticky-note {
      position: relative;
      background: #BEE5B0;
      padding: 40px;
      width: 100%;
      max-width: 420px;
      border-radius: 15px;
      box-shadow: 8px 8px 0 #497151;
    }

    /* Folded corner */
    .sticky-note::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      border-width: 0 0 40px 40px;
      border-style: solid;
      border-color: transparent transparent #497151 transparent;
      border-radius: 0 0 10px 0;
    }

    /* Tape effect */
    .sticky-note::after {
      content: "";
      position: absolute;
      top: -15px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 25px;
      background: #f5e6c8;
      opacity: 0.8;
      border-radius: 5px;
    }

    h3 {
      text-align: center;
      color: #3c7a4a;
      margin-bottom: 25px;
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
      background: #8ed39c;
      border: none;
      border-radius: 25px;
      color: black;
      font-weight: 600;
    }

    .btn-custom:hover {
      background: #76c787;
    }

    .alert {
      background: #ffe5e5;
      border: none;
      color: #c94b4b;
      border-radius: 10px;
    }

    .frog {
      position: absolute;
      bottom: -10px;
      right: -10px;
      width: 90px;
    }

    .small-link {
      font-size: 12px;
      color: #4c8c5a;
    }

    .small-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

<div class="container vh-100 d-flex justify-content-center align-items-center">
  <div class="sticky-note">

    <?php if (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
      <div class="alert text-center py-2 small">
        Invalid email or password!
      </div>
    <?php endif; ?>

    <h3>Login</h3>

    <form method="POST">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required placeholder="Enter email">
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required placeholder="Enter password">
      </div>

      <div class="d-flex justify-content-between pb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox">
          <label class="form-check-label small-link">
            Remember this account
          </label>
        </div>
        <a href="#" class="small-link">Forgot password?</a>
      </div>

      <div class="d-grid pb-2">
        <button type="submit" class="btn btn-custom btn-lg">Login</button>
      </div>

      <div class="text-center">
        <small>
          Don't have an account?
          <a href="signup.php" class="small-link fw-bold">Sign up</a>
        </small>
      </div>
    </form>

   
    <img src="ASSETS/frog.png" class="frog" alt="frog" style="width: 70px;">

  </div>
</div>

</body>
</html>