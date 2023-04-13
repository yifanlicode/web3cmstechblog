<?php

session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if form was submitted

  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  if (!validate_username($username) || !validate_email($email) || !validate_password($password)) {
    $error = "Please enter valid input.";
  } else {// Hash password
  $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

  register_user($username, $email, $password);
  login_user($username, $_POST['password']);
  }
}

function validate_username($username) {
  $pattern = "/^[a-zA-Z0-9_-]+$/";
  return preg_match($pattern, $username);
}

function validate_email($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_password($password) {
  $pattern = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/";
  return preg_match($pattern, $password);
}


// Function to login user
function login_user($username, $password)
{
  global $db;

  $username = trim($username);
  $password = trim($password);

  $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $hashed_password = $user['user_password'];
    if (password_verify($password, $hashed_password)) {
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['user_role'] = $user['user_role'];
      redirect('/admin');
    } else {
      return false;
    }
  } else {
    return false;
  }
}


// Function to register user
function register_user($username, $email, $password)
{
  global $db;

  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  $stmt = $db->prepare("INSERT INTO users (username, user_email, user_password) VALUES (:username, :email, :password)");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $password);
  $stmt->execute();

  // Redirect to login page after successful registration
  header("Location: index.php");
  exit();
}

function redirect($url)
{
  header("Location: $url");
  exit();
}

include 'includes/header.php';

?>


<!-- Register Form -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const errors = <?php echo json_encode($error); ?>;
    displayServerErrors(errors);
  });
</script>


<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <h1 class="text-center mb-4">Register</h1>

      <form action="register.php" method="post" id="register-form">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" oninput="validateUsername()" required>
          <span id="username-error" class="text-danger"></span>

        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" oninput="validateEmail()" required>
          <!-- Add error message element below email input -->
          <span id="email-error" class="text-danger"></span>
        </div>

        <!-- 注册表单 -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" oninput="validatePassword()" required>
          <!-- Add error message element below password input -->
          <span id="password-error" class="text-danger"></span>
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" oninput="validateConfirmPassword()" required>
          <!-- Add error message element below confirm password input -->
          <span id="confirm-password-error" class="text-danger"></span>
        </div>
        <button type="submit" class="btn btn-primary" id="register-button">Register</button>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>