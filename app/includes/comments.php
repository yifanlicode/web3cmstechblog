
<!-- Comments -->
<div class="container my-5">
  <h3 class="fw-bold mb-4">Comments</h3>
  <hr>

  <!-- Comment Form -->
  <?php if (isset($_SESSION['username'])): ?>
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
      <div class="media mb-3">
        <!-- <img src="https://via.placeholder.com/64x64" class="mr-3" alt="Commenter Image"> -->
        <div class="media-body">
        <h5 class="mt-0 fw-bold"><?= $comment['username'] ?></h5>
          <p><?= $comment['comment_content'] ?></p>
          <p class="text-muted fs-6">Commented on <?= $comment['comment_date'] ?></p>
        </div>
      </div>

  <?php
    endforeach;
  else :
  ?>

    <p>No comments yet. Be the first to comment!</p>
  <?php endif; ?>
</div>








