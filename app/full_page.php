<?php

/*******
 *
 * This is the full page view of a post
 * only the author of the post can edit or delete the post 
 ****************/

require('includes/connect.php');

// Get the post ID and validate it

$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

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
            <h1 class="fw-bold"><?= $postTitle ?></h1>
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
          <!-- updatedtiem -->
          <p class="fs-6 fw-bold text-muted mb-0">
            Updated on <span class="text-primary"><?= $postUpdatedTime ?></span>
          </p>
        </header>
        <hr>

        <!-- Article Image -->
        <img src="uploads/<?= $postImage ?>" alt="Post Image">

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