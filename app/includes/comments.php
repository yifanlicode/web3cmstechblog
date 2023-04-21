<!-- Comments -->
<div class="container my-5">
  <h3 class="fw-bold mb-4">Comments</h3>
  <hr>

  <?php

if (!isset($_SESSION['page_visit']) || $_SESSION['page_visit'] !== $comment_post_id) {
  unset($_SESSION['comment_content']);
  unset($_SESSION['captcha_try_count']);
  $_SESSION['page_visit'] = $comment_post_id;
}

  // If the CAPTCHA is not submitted correctly 
  if (isset($_POST['submit'])) { // Submit comment
    
    if ($_POST['captcha'] != $_SESSION['captcha']) { // Captcha is incorrect
      if (!isset($_SESSION['captcha_try_count'])) { // Set captcha try count
        $_SESSION['captcha_try_count'] = 1; // First try
      } else {
        $_SESSION['captcha_try_count'] += 1; // Increment try count
      }

      // If the captcha is incorrect less than 3 times, keep the comment content
      if ($_SESSION['captcha_try_count'] < 3) {
        $_SESSION['comment_content'] = $_POST['comment_content'];
        echo "
        <div class='alert alert-danger'>Captcha is incorrect, please try again.</div>
        ";
      } else {
        echo "<div class='alert alert-danger'>Captcha is incorrect over 3 times, please re-enter your comment.</div>";
        unset($_SESSION['comment_content']);
      }
    } else { // Captcha is correct
      unset($_SESSION['captcha_try_count']);
    }
  }
  ?>

  <!-- Comment Form -->
  <form action="<?= "full_page.php" . "?id=" . $comment_post_id ?>" method="post">
    <div class="form-group">
      <textarea class="form-control" name="comment_content" rows="4" placeholder="Write a comment">
        <?php if (isset($_SESSION['comment_content'])) : ?>
          <?= $_SESSION['comment_content'] ?>
        <?php endif; ?>
      </textarea>

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

    <p class="text-muted">No comments yet. Be the first to comment!</p>

  <?php endif; ?>
</div>