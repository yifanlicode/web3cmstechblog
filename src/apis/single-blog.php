<!-- Main Content -->
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <!-- Article -->
      <article class="bg-light p-4 rounded">
        <!-- Article Header -->
        <header class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <!-- Article Title -->
            <h1 class="fw-bold"><?= $postTitle ?></h1>
            <!-- Edit Button -->
            <a href="edit.php?id=<?= $post_id; ?>" class="btn btn-sm btn-outline-primary">Edit Post</a>
          </div>
          <!-- Article Meta -->
          <p class="fs-6 fw-bold text-muted mb-0">
  
          Category: <a href="#">
          <span class="text-primary"><?= $postCategory ?>
        </span></a>
</p>
          <p class="fs-6 fw-bold text-muted mb-0">
            Tag:
            <?php
            $tags = explode(',', $postTags);
            foreach ($tags as $tag) :
            ?>
              <a href="tag.php?name=<?= $tag ?>">
                <span class="text-primary"><?= $tag ?></span>
              </a>
            <?php endforeach; ?>
          </p>
          <p class="fs-6 fw-bold text-muted mb-0">
            Author:
            <a href="author.php?id=<?= $post['post_author'] ?>">
              <span class="text-primary"><?= $postAuthor ?></span>
            </a>
          </p>
          <p class="fs-6 fw-bold text-muted mb-0">
            Posted on <span class="text-primary"><?= $postDate ?></span>
          </p>

        </header>
        <hr>
        <!-- Article Content -->
        <p class="fs-5"><?= $postContent ?></p>
      </article>