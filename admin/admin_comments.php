
<?php
// Start session and check if user is an admin
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_role'] != 'admin') {
  header("Location: index.php");
  exit;
}

require('includes/connect.php');

// Fetch all comments with a 'pending' status
$query = "SELECT * FROM comments INNER JOIN users ON comments.comment_user_id = users.user_id WHERE comments.comment_status = 'pending' ORDER BY comment_date DESC";
$statement = $db->prepare($query);
$statement->execute();
$pending_comments = $statement->fetchAll();
$statement->closeCursor();

// If the admin approves a comment, update the comment status to 'approved'
if (isset($_POST['approve'])) {
  $comment_id = $_POST['comment_id'];
  $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = :comment_id";
  $statement = $db->prepare($query);
  $statement->bindValue(':comment_id', $comment_id);
  $statement->execute();
  $statement->closeCursor();
  header("Location: admin_comments.php");
  exit;
}

include 'includes/header.php';
?>

<div class="container my-5">
  <h2 class="mb-4">Pending Comments</h2>
  <?php if ($pending_comments) : ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Comment</th>
          <th scope="col">Author</th>
          <th scope="col">Date</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pending_comments as $comment) : ?>
          <tr>
            <td><?= $comment['comment_content'] ?></td>
            <td><?= $comment['username'] ?></td>
            <td><?= $comment['comment_date'] ?></td>
            <td>
              <form action="admin_comments.php" method="post">
                <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                <input type="submit" name="approve" value="Approve" class="btn btn-success">
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else : ?>
    <p>No pending comments.</p>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
