<?php
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

// Delete comment from database if requested
if (isset($_POST['delete_comment'])) {
    $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);

    // Delete comment from database
    $query = "DELETE FROM comments WHERE comment_id = :comment_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':comment_id', $comment_id);
    $statement->execute();
    $statement->closeCursor();

    // Redirect to comments page with success message
    header("Location: comments.php?message=Comment deleted successfully");
    exit();
}

// Fetch all comments from database
$query = "SELECT * FROM comments ORDER BY comment_id DESC";
$statement = $db->prepare($query);
$statement->execute();
$comments = $statement->fetchAll();
$statement->closeCursor();

// Include header
include "includes/admin_header.php";
?>

<div class="col-lg-3">
  <div class="list-group">
    <a href="user.php" class="list-group-item list-group-item-action">Users</a>
    <a href="posts.php" class="list-group-item list-group-item-action">Posts</a>
    <a href="categories.php" class="list-group-item list-group-item-action">Categories</a>
    <a href="comments.php" class="list-group-item list-group-item-action active" aria-current="page">Comments</a>
  </div>
</div>

<div class="col-lg-9">
  <div class="card mb-4">
    <div class="card-body">
      <h3 class="card-title">Comments</h3>

      <?php 
      if(isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']);
      }
      ?>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Post ID</th>
            <th>User ID</th>
            <th>Comment</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($comments as $comment) : ?>
            <tr>
              <td><?= $comment['comment_id'] ?></td>
              <td><?= $comment['comment_post_id'] ?></td>
              <td><?= $comment['comment_user_id'] ?></td>
              <td><?= $comment['comment_content'] ?></td>
              <td><?= $comment['comment_status'] ?></td>
              <td><?= $comment['comment_date'] ?></td>
              <td>
                <div class="d-flex justify-content-center">
                  <form method="post" action="comments.php" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                    <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>" />
                    <button type="submit" name="delete_comment" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> Delete</button>
                  </form>
                </div>
             
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    </div>
</div>

<?php include "includes/admin_footer.php"; ?>
