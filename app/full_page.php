<?php

/*******
 *
 * This is the full page view of a post
 * only the author of the post can edit or delete the post 
 ****************/

require('includes/connect.php');

//check the session
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}


// Get the post ID and validate it
$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$comment_post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (empty($post_id)) {
  header("Location: index.php");
  exit;
}

$query = "SELECT p.*, c.cat_title
          FROM posts p
          LEFT JOIN categories c ON p.post_category_id = c.cat_id
          WHERE p.post_id = :id
          ";

$statement = $db->prepare($query);
$statement->bindValue(':id', $post_id);
$statement->execute();
$post = $statement->fetch();
$statement->closeCursor();

if ($post) {
  $postTitle = $post['post_title'];
  $postContent = $post['post_content'];
  $postDate = $post['post_date'];
  $postImage = $post['post_image'];
  $postTags = $post['post_tags'];
  $postStatus = $post['post_status'];
  $postViewsCount = $post['post_views_count'];
  $postCommentCount = $post['post_comment_count'];
  $postDate = $post['post_date'];
  $postAuthor = $post['post_author'];
  $postCategory = $post['cat_title'];
  $postUpdatedTime = $post['update_date'];
} else {
  header("Location: index.php");
  exit;
}


include 'includes/header.php';

?>


<!-- Main Content -->
<div class="container-fluid my-5">
  <div class="row justify-content-center">

    <!-- Post Content -->
    <div class="col-lg-8">
      <!-- Article -->
      <article class="bg-light p-4 rounded">
        
      <!-- Article Header -->
        <header class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <!-- Article Title -->
            <h3 class="fw-bold"><?= $postTitle ?></h3>
            <!-- Edit Button -->
            <?php
            if (isset($_SESSION['username']) && $_SESSION['username'] === $postAuthor) {
            ?>
              <div class="d-flex">
                <a href="edit_page.php?id=<?= $post_id ?>" class="btn btn-primary me-2">Edit</a>
              </div>
            <?php
            }
            ?>
          </div>

          </header>
          <!-- Article Meta -->
          <ul class="list-inline text-muted mb-2">
            <li class="list-inline-item">
              Author:
              <a href="author.php?id=<?= $post['post_author'] ?>" class="text-primary">
                <?= $postAuthor ?>
              </a>
            </li>
            <li class="list-inline-item">
              Category: <a href="#" class="text-primary"><?= $postCategory ?></a>
            </li>
            <li class="list-inline-item">
                Tag:
                <?php
                $tags = explode(',', $postTags);
                $tagCount = count($tags);
                for ($i = 0; $i < 4 && $i < $tagCount; $i++) {
                  $tag = $tags[$i];
                ?>
                  <a href="tag.php?name=<?= $tag ?>" class="text-primary">
                    <?= $tag ?>
                  </a>
                  <?php if ($i < $tagCount - 1) echo ', '; ?>
                <?php } ?>
              </li>

          </ul>
          <ul class="list-inline text-muted mb-2">
            <li class="list-inline-item">
              Posted: <span class="text-primary"><?= $postDate ?></span>
            </li>
            <li class="list-inline-item">
              Updated: <span class="text-primary"><?= $postUpdatedTime ?></span>
            </li>
          </ul>


        <hr>

        <!-- Article Image -->
        <?php if (!empty($postImage)): ?>
          <div class="d-flex justify-content-center mb-3">
            <img class="mg-thumbnail shadow" src="uploads/<?= $postImage ?>" alt="Post Image">
          </div>
            <?php endif; ?>

        <!-- Article Content -->
        <p class="fs-5"><?= $postContent ?></p> </article>

    </div>

    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>
    <!-- End of sidebar -->
  </div>
</div>
<!-- End of Main Content -->

<!-- Include comments.php -->
<?php include 'includes/comments.php'; ?>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>
<!-- End of Footer -->