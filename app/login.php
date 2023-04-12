<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if form was submitted

  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  $error = [
    'username' => '',
    'password' => ''
  ];

  // Check if username is an email address
  if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $user = get_user_by_email($username);
  } else {
    $user = get_user_by_username($username);
  }

  if (!$user) {
    $error['username'] = 'Invalid username or email';
  } else {
    $hashed_password = $user['user_password'];
    if (!password_verify($password, $hashed_password)) {
      $error['password'] = 'Incorrect password';
    }
  }

  foreach ($error as $key => $value) {
    if(empty($value)) {
      unset($error[$key]);
    }
  } // foreach

  if(empty($error)) {
    login_user($user['username'], $password);
  }
} 

function get_user_by_username($username) {
  global $db;
  $query = "SELECT * FROM users WHERE username = :username";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_user_by_email($email) {
  global $db;
  $query = "SELECT * FROM users WHERE user_email = :email";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to login user
function login_user($username, $password) {
  global $db;

  $username = trim($username);
  $password = trim($password);

  $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if($user) {
      $hashed_password = $user['user_password'];
      if(password_verify($password, $hashed_password)) {
          $_SESSION['user_id'] = $user['user_id'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['user_role'] = $user['user_role'];
          redirect('/admin');
      } else {
          $error = 'Incorrect password';
      }
  } else {
      $error = 'Invalid username or email';
  }
}

function redirect($url) {
  header("Location: $url");
  exit();
}

include 'includes/header.php';
  
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h1 class="text-center mb-4">Login</h1>
            <?php if(isset($error)) { ?>
  <div class="alert alert-danger" role="alert">
    <?php 
    echo $error['username']; 
    if(isset($error['password'])) {
      echo ' ' . $error['password'];
    }
    ?>
  </div>
<?php } ?>

            <form id="login-form" action="login.php" method="post" autocomplete="off">
                <div class="mb-3">
                <label for="username" class="form-label">Username or Email</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>

            </form>
        </div>
    </div>
    <!-- if user donot have a account, he can register one  -->
    <div class="row justify-content-center mt-3">
        <div class="col-md-8 col-lg-6">
            <p class="text-center">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>

