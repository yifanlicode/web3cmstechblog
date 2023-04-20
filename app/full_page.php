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
echo "comment_post_id: $comment_post_id<br>";

if (isset($_POST['submit'])) {
  // Get the comment content
  $content = filter_input(INPUT_POST, 'comment_content', FILTER_SANITIZE_STRING);
  echo "content: $content<br>";
  $user_id = $_SESSION['user_id'];
  echo "user_id: $user_id<br>";

  try {
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
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
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
  </div>
</div>
<!-- End of Main Content -->

<!-- Include comments.php -->
<?php include 'includes/comments.php'; ?>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>
<!-- End of Footer -->