<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if form was submitted
 
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $confirm_password = trim($_POST['confirm_password']);

  $error = [
    'username' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => ''
  ];
  if(strlen($username) < 4) {
    $error['username'] = 'Username needs to be longer';
  }

  if($username == '') {
    $error['username'] = 'Username cannot be empty';
  }

  if(username_exists($username)) {
    $error['username'] = 'Username already exists, pick another one';
  }

  if($email == '') {
    $error['email'] = 'Email cannot be empty';
  }

  if(email_exists($email)) {
    $error['email'] = 'Email already exists, <a href="index.php">Please login</a>';
  }

  if($password == '') {
    $error['password'] = 'Password cannot be empty';
  }

  if($confirm_password == '') {
    $error['confirm_password'] = 'Confirm password cannot be empty';
  }

  if($password !== $confirm_password) {
    $error['confirm_password'] = 'Passwords do not match';
  }

  foreach ($error as $key => $value) {
    if(empty($value)) {
      unset($error[$key]);
    }
  } // foreach

  if(empty($error)) {
    register_user($username, $email, $password);
    login_user($username, $password);
  }
} 

function username_exists($username) {
  global $db;
  $query = "SELECT username FROM users WHERE username = :username";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $count = $stmt->rowCount();
  return $count > 0;
}

function email_exists($email) {
  global $db;

  $sql = "SELECT user_id FROM users WHERE user_email = :email";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':email', $email);
  $stmt->execute();

  if($stmt->rowCount() > 0) {
    return true;
  } else {
    return false;
  }
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
          return false;
      }
  } else {
      return false;
  }
}

// Function to register user
function register_user($username, $email, $password) {
  global $db;

  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  
  // Hash password
  $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

  $stmt = $db->prepare("INSERT INTO users (username, user_email, user_password) VALUES (:username, :email, :password)");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':password', $password);
  $stmt->execute();

  // Redirect to login page after successful registration
  header("Location: login.php");
  exit();
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
                <h1 class="text-center mb-4">Register</h1>
                
                <form id="register-form" action="register.php" method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <script src="../../public/js/register.js"></script>
    <?php include 'includes/footer.php'; ?>
    <!-- End of Footer -->

   

