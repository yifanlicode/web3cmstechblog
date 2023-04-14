<?php

/*******
 *
 * This is the view page for the PHP blog.
 * It displays a single blog post.
 ****************/

// Check if the user is the author of the post

require('includes/connect.php');

// Get the post id
$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Get the post from the database
$query = "SELECT posts.post_id, posts.post_title,
 posts.post_content, posts.post_date, posts.post_image, posts.post_tags,
  posts.post_status, posts.post_views_count, posts.post_comment_count, 
  posts.post_date, posts.post_author, categories.cat_title
   FROM posts LEFT JOIN categories ON posts.post_category_id = categories.cat_id 
   WHERE posts.post_id = :id";

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
} else {
  header("Location: index.php");
  exit;
}

?>



<?php include 'includes/header.php'; ?>

<!-- Main Content -->
<div class="container-fluid my-5">
  <div class="row justify-content-center">

    <div class="row">
      <div class="col-lg-9">
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
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['post_author']) : ?>
                      <a href="edit.php?id=<?= $post_id; ?>" class="btn btn-sm btn-outline-primary">Edit Post</a>
                    <?php endif; ?>
                  </div>
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

            <!-- Article Image -->
            <img src="<?= $postImage ?>" alt="Post Image">

            <!-- Article Content -->
            <p class="fs-5"><?= $postContent ?></p>
            </article>

          </div>

          <!-- Sidebar -->
          <?php include 'includes/sidebar.php'; ?>
          <!-- End of sidebar -->

          <!-- comments -->
          <!-- Include comments.php -->
          <?php include 'comments.php'; ?>


        </div>
      </div>
      <!-- End of Main Content -->


      <!-- Footer -->
      <?php include 'includes/footer.php'; ?>
      <!-- End of Footer -->

      <!-- JS Scripts -->
      <!-- JavaScript Bundle with Popper -->
      <script rel="stylesheet" href="../../public/css/bootstrap.min.css" integrity="sha384-Ed2grpAAfUgVfDfBVHPj9+15Ek329o8swXCSgTxHkzubDidTNJ3lk2bXJHr3Tfz9" crossorigin="anonymous"></script>
      </body>

      </html>