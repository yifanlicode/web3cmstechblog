<?php

/*******
 *
 * This is the view page for the PHP blog.
 * It displays a single blog post.
 ****************/

require('connect.php');

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



<!DOCTYPE html>
<html>
<head>
  <!-- SEO  -->
  <meta name="description" content="Web3 Launchpad is a one-stop destination for anyone looking to learn and work in the Web3 and blockchain industry.">
  <meta name="keywords" content="Web3, Blockchain, Dev, Learning, Tutorials">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Web3 <?= $post['title'] ?> </title>
  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="../../public/css/bootstrap.min.css">
  <link rel="stylesheet/scss" href="../../public/css/_bootswatch.scss">
  <link rel="stylesheet" href="../../public/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-ai3DlHwZ5K5W5GTHYYXcBbXEikRyvCtWXBx8Hg+PpDEYw+ZRMHnZa8nXQ2viM59JScfzsFbSVn71MZ0kgQGbQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-ai3DlHwZ5K5W5GTHYYXcBbXEikRyvCtWXBx8Hg+PpDEYw+ZRMHnZa8nXQ2viM59JScfzsFbSVn71MZ0kgQGbQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>


<body>
  <!-- Header -->
  <?php include(__DIR__ . '/header.php'); ?>
  <!-- End of Header -->

 <!-- Main Content -->
 <div class="container-fluid my-5">
 <div class="row justify-content-center">

  <div class="row">
    <div class="col-lg-9">
      <!-- Include single-blog.php -->
      <?php include 'single-blog.php'; ?>
    </div>

    
      <!-- Sidebar -->

      <div class="col-lg-2">
      <div class="sidebar">
      
        <!-- Tags cloud -->
        <div>
          <h5>Tags Cloud</h5>
        </div>
        <!-- Include tags-cloud.php -->

        <!-- Categories -->
        <div>
          <h5>Categories</h5>
        </div>
        <!-- Include categories.php -->


          <!-- Popular articles -->
          <div>
          <h5> Popular Articles</h5>
          </div>
        <!-- Include popular-articles.php -->

      </div>
    </div>

    
  </div>
</div>
<!-- End of Main Content -->


  <!-- Footer -->
  <?php include 'footer.php'; ?>
  <!-- End of Footer -->

  <!-- JS Scripts -->
  <!-- JavaScript Bundle with Popper -->
  <script rel="stylesheet" href="../../public/css/bootstrap.min.css" integrity="sha384-Ed2grpAAfUgVfDfBVHPj9+15Ek329o8swXCSgTxHkzubDidTNJ3lk2bXJHr3Tfz9" crossorigin="anonymous"></script>
</body>
</html>