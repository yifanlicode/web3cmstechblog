<?php

require_once 'includes/connect.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

  // validate 2 passwords match 
  if ($password !== $confirm_password) {
    echo 'Passwords do not match';
  } else {
    $result = register_user($username, $email, $password);
    if ($result === "success") {
      // Redirect to login page after successful registration
      header("Location: login.php");
      exit();
    } else {
      echo "Registration failed. Please try again.";
    }
  }
}

// Register user
function register_user($username, $email, $password)
{
  global $db;

  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  // Hash password
  $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

  try {
    // Insert user into database
    $stmt = $db->prepare("INSERT INTO users (username, user_email, user_password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    // Log in user
    $user_id = $db->lastInsertId();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['user_role'] = 'registered';

    return "success";
  } catch(PDOException $e) {
    return "error";
  }
}

include 'includes/header.php';
?>


<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <h1 class="text-center mb-4">Register</h1>
      <div id="form-error" class="text-danger"></div>

      <form action="register.php" method="post" id="register-form">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
          <span id="username-error" class="text-danger"></span>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" required>
          <!-- Add error message element below email input -->
          <span id="email-error" class="text-danger"></span>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
          <span id="password-error" class="text-danger"></span>
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          <span id="confirm-password-error" class="text-danger"></span>
        </div>
        <button type="submit" class="btn btn-primary" id="register-button">Register</button>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
