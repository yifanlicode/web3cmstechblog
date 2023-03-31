<!-- Main Content -->
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <!-- Article -->
      <article>
        <!-- Article Header -->
        <header>
          <!-- Article Title -->
          <h1 class="fw-bold mb-4"><?= $postTitle ?></h1>
          <!-- Article Meta -->
          <p class="fs-6 fw-bold text-muted mb-0">
            Category: <?= $postCategory ?>
          </p>
          <p class="fs-6 fw-bold text-muted mb-0">
            Tag: <?= $postTags ?>
          </p>
          <p class="fs-6 fw-bold text-muted mb-0">
            Author: <?= $postAuthor ?>
          </p>
          <p class="fs-6 fw-bold text-muted mb-0">
            Posted on <?= $postDate ?>
          </p>
        </header>
        <hr>
        <!-- Article Content -->
        <p class="fs-5 mb-5"><?= $postContent ?></p>
        <!-- Article Footer -->
        <footer>
          <p class="fs-6 fw-bold text-muted mb-0">
            <a href="edit.php?id=<?= $post_id; ?>">Edit Post</a>
          </p>
        </footer>
      </article>

  
      <!-- Comments -->
      <div class="my-5">
        <h2 class="fs-4 fw-bold mb-4">Comments</h2>
        <form action="comment.php" method="post">
          <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" name="author">
          </div>
          <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="3"></textarea>
          </div>
          <input type="hidden" name="post_id" value="<?= $post_id ?>">
          <button type="submit" class="btn btn-primary">Submit</button>
        <hr>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Main Content -->