<?php
session_start();

require_once 'connect.php';

if(isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

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
          header('Location: index.php');
          exit;
      } else {
          $error = "Invalid username or password";
      }
  } else {
      $error = "Invalid username or password";
  }
}

include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h1 class="text-center mb-4">Login</h1>
            <?php if(isset($error)) { ?>
              <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
              </div>
            <?php } ?>
            <form id="login-form" action="login.php" method="post" autocomplete="off">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
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

<?php include 'includes/footer.php'; ?>
