<!-- Comments -->
<div class="container my-5">
  <h3 class="fw-bold mb-4">Comments</h3>
  <hr>

  <!-- Comment Form -->
  <?php if (isset($_SESSION['username'])) : ?>
    <form action="<?= "full_page.php" . "?id=" . $comment_post_id ?>" method="post">
      <div class="form-group">
        <textarea class="form-control" name="comment_content" rows="4" placeholder="Write a comment"></textarea>
      </div>
      <input type="submit" name="submit" value="Submit" class="btn btn-primary">
    </form>
  <?php else : ?>
    <p>Please <a href="login.php">log in</a> or <a href="register.php">register</a> to post a comment.</p>
  <?php endif; ?>
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

    <p>No comments yet. Be the first to comment!</p>
  <?php endif; ?>
</div>