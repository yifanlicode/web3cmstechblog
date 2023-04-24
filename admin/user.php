<?php

//check if the session started if not start it
if (!isset($_SESSION)) {
    session_start();
}

require 'includes/connect.php';

// check if the logged in user is an admin
if ($_SESSION['user_role'] == 'admin') {
    // if the user is an admin, show the page
    // echo "Welcome Admin you can now manage the users";
} else {
    // if the user is not an admin, and prints a message
    echo "You are not an admin Please login again";
}

//Retrieve all users from the database
$query = "SELECT * FROM users";
$statement = $db->prepare($query);
$statement->execute();
$users = $statement->fetchAll();
$statement->closeCursor();


//Add a new user to the database
if (isset($_POST['add_user'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'user_email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'user_password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST, 'user_role', FILTER_SANITIZE_STRING);

    // validate 2 passwords match 
    if ($password !== $confirm_password) {
        echo 'Passwords do not match, please try again';
    } else {
        $result = register_user($username, $email, $password, $role);

        if ($result === "success") {
        // head to the users page after successful registration
        header("Location: user.php");
        exit();
        } else {
        echo "Creation failed. Please try again.";
        }
    }
}

// Add a new user to the database function
function register_user($username, $email, $password,$role)
{
  global $db;
  $username = trim($username);
  $email = trim($email);
  $password = trim($password);
  $role = trim($role);

  $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

  try {
    // Insert user into database
    $query = "INSERT INTO users
             (username, user_email, user_password, user_role) 
             VALUES (:username, :email, :password, :role)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
    $stmt->closeCursor();
    return "success";
  } catch(PDOException $e) {
    return "error";
  }
}

  
//Delete user data from database if clicked on the delete button

if (isset($_POST['delete_user_id'])) {
    // retrieve user id from form data
    $user_id = filter_input(INPUT_POST, 'delete_user_id', FILTER_VALIDATE_INT);

    $query = "DELETE FROM users WHERE user_id = :user_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();
    $statement->closeCursor();
 // set success message and redirect to user page
    $_SESSION['message'] = "User deleted successfully";
    header("Location: user.php");
    exit();
    } else {
    // set error message if user_id is invalid
    $_SESSION['error'] = "Invalid user ID";
}


// Path: admin/includes/admin_header.php
include "includes/admin_header.php";

?>


 <!-- Dashboard navigation -->
 <div class="col-lg-3">
      <div class="list-group">
        <a href="user.php" class="list-group-item list-group-item-action active"  >Users</a>
        <a href="posts.php" class="list-group-item list-group-item-action">Posts</a>
        <a href="categories.php" class="list-group-item list-group-item-action">Categories</a>
        <a href="comments.php" class="list-group-item list-group-item-action">Comments</a>
      </div>
    </div>
        <!-- Dashboard content -->
                 
        <div class="col-lg-9">
            <h3>Manage Users</h3>
            <hr>
            <!-- Display all users in a table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>User Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //Display each user in a row of the table
                            foreach ($users as $user) : ?>
                                <tr>
                                    <td><?php echo $user['user_id']; ?></td>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['user_email']; ?></td>
                                    <td><?php echo $user['user_role']; ?></td>
                                    <td>
                                <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <form method="post" action="user.php" class="d-inline">
                                <input type="hidden" name="delete_user_id" value="<?php echo $user['user_id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Add new user form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Add New User</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="user.php">
                            <div class="form-group mb-3">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                            </div>
                            <div class="form-group mb-3">
                                <label for="user_email">Email address</label>
                                <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter email">
                            </div>
                            <div class="form-group mb-3">
                                <label for="user_password">Password</label>
                                <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Password">
                            </div>
                            <div class="form-group mb-3">
                                <label for="user_password">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Password">
                            </div>
                            <div class="form-group mb-3">
                                <label for="user_role">User Role</label>
                                <select class="form-select" id="user_role" name="user_role">
                                    <option value="admin">Admin</option>
                                    <option value="registered">Registered</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary" name="add_user">Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->



    <?php include "includes/admin_footer.php" ?>