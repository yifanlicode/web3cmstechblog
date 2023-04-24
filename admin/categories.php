<?php


// CREATE TABLE `categories` (
//   `cat_id` int(3) NOT NULL AUTO_INCREMENT,
//   `cat_title` varchar(255) NOT NULL DEFAULT '',
//   `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
//   `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//   PRIMARY KEY (`cat_id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


// check if the session started if not start it
if (!isset($_SESSION)) {
    session_start();
}

// check if user logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}
require('includes/connect.php');

// Fetch all categories from database
$query = "SELECT * FROM categories ORDER BY cat_id DESC";
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll();
$statement->closeCursor();

// Add new category to the database


// Add new category to database if form is submitted
if (isset($_POST['add_category'])) {
  $cat_title = filter_input(INPUT_POST, 'cat_title', FILTER_SANITIZE_STRING);

  // Check if category title is empty
  if (empty($cat_title)) {
    $error = "Please enter a category title";
  } else {
    // Insert new category into database
    $query = "INSERT INTO categories (cat_title) VALUES (:cat_title)";
    $statement = $db->prepare($query);
    $statement->bindParam(':cat_title', $cat_title);
    $statement->execute();
    $statement->closeCursor();

    // Redirect to categories page with success message
    header("Location: categories.php?message=Category added successfully");
    exit();
  }
}

// Update category in database if form is submitted
if (isset($_POST['update_category'])) {
  $cat_id = filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT);
  $cat_title = filter_input(INPUT_POST, 'cat_title', FILTER_SANITIZE_STRING);

  // Check if category title is empty
  if (empty($cat_title)) {
    $error = "Please enter a category title";
  } else {
    // Update category in database
    $query = "UPDATE categories SET cat_title = :cat_title WHERE cat_id = :cat_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':cat_title', $cat_title);
    $statement->bindParam(':cat_id', $cat_id);
    $statement->execute();
    $statement->closeCursor();

    // Redirect to categories page with success message
    header("Location: categories.php?message=Category updated successfully");
    exit();
  }
}

// Delete category from database if requested
if (isset($_POST['delete_category'])) {
  $cat_id = filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT);

  // Delete category from database
  $query = "DELETE FROM categories WHERE cat_id = :cat_id";
  $statement = $db->prepare($query);
  $statement->bindParam(':cat_id', $cat_id);
  $statement->execute();
  $statement->closeCursor();

  // Redirect to categories page with success message
  header("Location: categories.php?message=Category deleted successfully");
  exit();
}

// Include header
include "includes/admin_header.php";
?>


<div class="col-lg-3">
  <div class="list-group">
    <a href="user.php" class="list-group-item list-group-item-action">Users</a>
    <a href="posts.php" class="list-group-item list-group-item-action">Posts</a>
    <a href="categories.php" class="list-group-item list-group-item-action active" aria-current="page">Categories</a>
    <a href="comments.php" class="list-group-item list-group-item-action">Comments</a>
  </div>
</div>

<div class="col-lg-9">
  <div class="card mb-4">
    <div class="card-body">
      <h3 class="card-title">Categories</h3>

      <?php 
      if(isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']);
      }
      ?>

      <a href="#add-category" data-bs-toggle="modal" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Add Category</a>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT * FROM categories ORDER BY cat_id DESC";
          $statement = $db->prepare($query);
          $statement->execute();
          $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
          $statement->closeCursor();

          foreach($categories as $category) {
            echo "<tr>
                    <td>{$category['cat_id']}</td>
                    <td>{$category['cat_title']}</td>
                    <td>{$category['created_at']}</td>
                    <td>{$category['updated_at']}</td>
                    <td>
                      <div class='d-flex justify-content-center'>
                        <a href='#edit-category-{$category['cat_id']}' data-bs-toggle='modal' class='btn btn-sm btn-warning me-2'><i class='fas fa-edit'></i> Edit</a>
                        <form method='post' action='categories.php' onsubmit=\"return confirm('Are you sure you want to delete this category?');\">
                          <input type='hidden' name='cat_id' value='{$category['cat_id']}' />
                          <button type='submit' name='delete_category' class='btn btn-sm btn-danger'><i class='fas fa-trash-alt'></i> Delete</button>
                        </form>
                      </div>
                    </td>
                  </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="add-category" tabindex="-1" aria-labelledby="add-category-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add-category-label">Add Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="categories.php">
        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="cat_title">Category Title</label>
            <input type="text" name="cat_title" id="cat_title" class="form-control" required />
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" id="edit-category-form">
          <input type="hidden" name="cat_id" id="cat_id">
          <div class="form-group">
            <label for="cat_title">Category Title</label>
            <input type="text" class="form-control" id="cat_title" name="cat_title" required>
          </div>
          <button type="submit" class="btn btn-primary" name="edit_category">Update Category</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End Edit Category Modal -->

<?php include "includes/admin_footer.php"; ?>


