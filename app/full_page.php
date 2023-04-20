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

$comment_post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//echo "comment_post_id: $comment_post_id<br>";

if (isset($_POST['submit'])) {
  // Get the comment content
  $content = filter_input(INPUT_POST, 'comment_content', FILTER_SANITIZE_STRING);
  //echo "content: $content<br>";
  $user_id = $_SESSION['user_id'];
  //echo "user_id: $user_id<br>";

    // Check if the entered captcha is correct
    if ($_POST['captcha'] != $_SESSION['captcha']) {
    // Captcha is incorrect
    $error = "Captcha is incorrect, please try again.";
     } else {
    // Insert the comment into the database
    $query = "INSERT INTO comments 
              (comment_post_id, comment_user_id, comment_content, comment_status, comment_date)
            VALUES 
             (:post_id, :user_id, :content, 'pending', NOW())";

    $query = "INSERT INTO comments (comment_post_id, comment_user_id, comment_content, comment_date)
    VALUES (:post_id, :user_id, :content, NOW())";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $comment_post_id);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':content', $content);
    $statement->execute();
    $statement->closeCursor();
    echo "Comment submitted successfully<br>";
  }
}

// Get the comments for the post from the database and display them on the page 
$query = "SELECT * FROM comments INNER JOIN users ON comments.comment_user_id = users.user_id WHERE comments.comment_post_id = :id AND comments.comment_status = 'approved' ORDER BY comment_date DESC";
  $statement = $db->prepare($query);
  $statement->bindValue(':id', $post_id);
  $statement->execute();
  $comments = $statement->fetchAll();
  $statement->closeCursor();


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