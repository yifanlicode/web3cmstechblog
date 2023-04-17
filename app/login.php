<!-- - 

Logins are handled by way of an HTML form submitted to a PHP script.
- There must be some sort of message or indication that the login was successful.
- If the username and/or the password was incorrect the user should be shown a login failure message.
- PHP session should be used to remember successfully logged in users.
- There must also be some way for the user to log out which also involve PHP session. 

-->


<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}


require_once 'includes/connect.php';


$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Sanitize the input data (remove html tags, special characters, etc.)
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  if (!empty($username) && !empty($password)) {
    $login_result = login_user($username, $password);

    if ($login_result === 'invalid_username_email') {
      $error = 'Login failed. Please enter a valid username or email.';
    } elseif ($login_result === 'incorrect_password') {
      $error = 'Please enter the correct password';
    } else {
      $error = ''; // No error
    }
  } else {
    $error = 'Please fill in all fields.';
  }
}


function login_user($username, $password)
{
  global $db;

  $username = trim($username);
  $password = trim($password);

  $stmt = $db->prepare("SELECT * FROM users WHERE username = :username OR user_email = :email LIMIT 1");
  $stmt->bindParam(':username', $username); // Bind the username to the query
  $stmt->bindParam(':email', $username); // Bind the email to the query
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $hashed_password = $user['user_password'];
    if (password_verify($password, $hashed_password)) {
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['user_role'] = $user['user_role'];

      if ($user['user_role'] === 'admin') {
        $_SESSION['message'] = 'Welcome, admin!';
        header('Location: ../admin/index.php');
      } else {
        $_SESSION['message'] = 'Welcome, ' . $user['username'] . '!';
        header('Location: index.php');
      }
      exit();
    } else {
      return 'incorrect_password';
    }
  } else {
    return 'invalid_username_email';
  }
}

include 'includes/header.php';
?>

<!-- Login  -->
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

      <h1 class="text-center mb-4">Login</h1>
      <?php if (isset($_SESSION['message'])) : ?>
        <div class="alert alert-danger"><?php echo $_SESSION['message']; ?></div>
        <?php unset($_SESSION['message']); ?>
      <?php endif; ?>

      <form id="login-form" action="login.php" method="post" autocomplete="off">
        <div class="mb-3">
          <label for="username" class="form-label">Username or Email Address</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <?php if (isset($_POST['username']) && $error) : ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
          </div>
        <?php endif; ?>


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