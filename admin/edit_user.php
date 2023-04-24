<?php
// check if the session started if not start it


require('includes/connect.php');

$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

//Fetch User Data from database
$query = "SELECT * FROM users WHERE user_id = :user_id";
$statement = $db->prepare($query);
$statement->bindParam(':user_id', $user_id);
$statement->execute();
$user = $statement->fetch();
$statement->closeCursor();


// Update user data in the database if the form is submitted
if (isset($_POST['update_user'])) {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
  $new_password = filter_input(INPUT_POST, 'user_password', FILTER_SANITIZE_STRING);

  //Check the username and email address and role if they are empty
  if (empty($username) || empty($email) || empty($role) || empty($new_password)) {
    $error = "Please fill in the required fields: Title, Content, Category";
  } else {
      
      $hashed_password = password_hash($new_password, PASSWORD_BCRYPT, array('cost' => 12));

    //Update user data in the database
    $query = "  UPDATE users SET 
                username = :username, 
                user_email = :email, 
                user_role = :role,
                user_password = :hashed_password
                WHERE user_id = :user_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':username', $username);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':role', $role);
    $statement->bindParam(':hashed_password', $hashed_password);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $statement->closeCursor();

    //Set a message to be displayed on the users page
    $_SESSION['message'] = "User updated successfully";

    //Redirect to the users page
    header("Location: user.php");
    exit();
  }
}

// include header
include "includes/admin_header.php";
?>



<div class="col-lg-3">
      <div class="list-group">
      <a href="user.php" class="list-group-item list-group-item-action">Users</a>
        <a href="posts.php" class="list-group-item list-group-item-action">Posts</a>
        <a href="categories.php" class="list-group-item list-group-item-action">Categories</a>
        <a href="comments.php" class="list-group-item list-group-item-action">Comments</a>
      </div>
    </div>
  <!-- Dashboard content -->
  <div class="col-lg-9">
    <h3>Edit User Information</h3>
    <div class="card mb-4">
      <div class="card-body">
        <!-- Dashboard content -->
        <form method="post" action="edit_user.php?id=<?php echo $user['user_id']; ?>">
          <div class="form-group mb-3">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="<?php echo $user['username']; ?>">
          </div>
          <div class="form-group mb-3">
            <label for="user_email">Email address</label>
            <input type="email" class="form-control" id="user_email" name="email" placeholder="Enter email" value="<?php echo $user['user_email']; ?>">
          </div>
          <div class="form-group mb-3">
            <label for="user_password">New Password</label>
            <input type="password" class="form-control" id="user_password" name="user_password" placeholder="New Password">
          </div>
          <div class="form-group mb-3">
            <label for="user_role">User Role</label>
            <select class="form-select" id="user_role" name="role">
              <option value="admin" <?php if ($user['user_role'] == 'admin') {
                                      echo 'selected';
                                    } ?>>Admin</option>
              <option value="registered" <?php if ($user['user_role'] == 'registered') {
                                            echo 'selected';
                                          } ?>>Registered</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary" name="update_user">Update User</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<?php include "includes/admin_footer.php" ?>