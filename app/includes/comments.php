<!-- Comments -->
<?php

if (!isset($_SESSION['page_visit']) || $_SESSION['page_visit'] !== $comment_post_id) {
  unset($_SESSION['comment_content']);
  unset($_SESSION['captcha_try_count']);
  $_SESSION['page_visit'] = $comment_post_id;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit'])) {
    
      if ($_POST['captcha'] != $_SESSION['captcha']) {

          if (!isset($_SESSION['captcha_try_count'])) {
              $_SESSION['captcha_try_count'] = 0;
          } else {
              $_SESSION['captcha_try_count'] += 1; // Increment try count
          }
      } else {
          // Captcha is correct
          unset($_SESSION['captcha_try_count']);
          unset($_SESSION['comment_content']);

          $content = filter_input(INPUT_POST, 'comment_content', FILTER_SANITIZE_STRING);

          // Insert the comment into the database
          $query = "INSERT INTO comments 
          (comment_post_id, comment_user_id, comment_content, comment_status, comment_date)
          VALUES 
          (:post_id, :user_id, :content, 'approved', NOW())";

          $statement = $db->prepare($query);
          $statement->bindValue(':post_id', $comment_post_id);
          $statement->bindValue(':user_id', $user_id);
          $statement->bindValue(':content', $content);
          $statement->execute();
          $statement->closeCursor();
      }

      /// Display error messages based on the try count
      if (isset($_SESSION['captcha_try_count'])) {
          if ($_SESSION['captcha_try_count'] == 0) {
              $error = "Incorrect captcha, try again or you have to re-enter your comment. ";
              $_SESSION['comment_content'] = $_POST['comment_content'];
          } else {
              $error = "Incorrect captcha, You have tried " . strval($_SESSION['captcha_try_count'] + 1) . " time(s), please re-enter your comment.";
              unset($_SESSION['comment_content']);
          }
      }
  }

// Get the comments for the post from the database and display them on the page 
$query = "SELECT * FROM comments INNER JOIN users 
          ON comments.comment_user_id = users.user_id
          WHERE comments.comment_post_id = :id 
          AND comments.comment_status = 'approved' ORDER BY comment_date DESC";

  $statement = $db->prepare($query);
  $statement->bindValue(':id', $comment_post_id);
  $statement->execute();
  $comments = $statement->fetchAll();
  $statement->closeCursor();


?>


<div class="container my-5">
  <h3 class="fw-bold mb-4">Comments</h3>
  <hr>

  <!-- Display error message -->
  <?php if (isset($error)) : ?>
    <div class="alert alert-danger" role="alert">
      <?= $error ?>
    </div>
  <?php endif; ?>


        <form action="<?= "full_page.php" . "?id=" . $comment_post_id ?>" method="post" onsubmit="return validateComment();">
        <div class="form-group">
          <textarea class="form-control" name="comment_content" rows="4" placeholder="Write a comment" required><?php if (isset($_SESSION['comment_content'])) : ?><?= $_SESSION['comment_content'] ?><?php endif; ?></textarea>
        </div>
        <img src="includes/captcha.php" alt="Captcha">
        <input type="text" name="captcha" required>
        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
      </form>


  <hr>

  <!-- Display comments -->
  <?php
  if ($comments) :
    foreach ($comments as $comment) :
  ?>
      <div class="media mb-3 border p-3">
        <img src="../public/images/userimage.jpg" width="64" height="64" class="mr-3 rounded-circle" alt="Commenter Image">
        <div class="media-body">
          <p class="mt-0 fw-bold d-inline-block mr-3"><?= $comment['username'] ?></p>
          <p class="text-muted fs-6 d-inline-block"><?= $comment['comment_date'] ?></p>
          <p><?= $comment['comment_content'] ?></p>
        </div>
      </div>


    <?php
    endforeach;
  else :
    ?>

    <p class="text-muted">
      No comments yet. Be the first to comment!</p>

  <?php endif; ?>
</div>