
<!-- -- Posts表
CREATE TABLE `posts` (
  `post_id` int(3) NOT NULL AUTO_INCREMENT,
  `post_category_id` int(3) NOT NULL,
  `post_title` varchar(255) NOT NULL DEFAULT '',
  `post_author` varchar(255) NOT NULL DEFAULT '',
  `post_user` varchar(255) NOT NULL DEFAULT '',
  `post_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_image` LONGTEXT NOT NULL,
  `post_content` text NOT NULL,
  `post_tags` varchar(255) NOT NULL DEFAULT '',
  `post_comment_count` int(11) NOT NULL DEFAULT '0',
  `post_status` varchar(255) NOT NULL DEFAULT 'draft',
  `post_views_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 -->

<?php

require('authenticate.php');
require('connect.php');

// Check if the form is submitted

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the form data
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $category_id = isset($_POST['category']) ? $_POST['category'] : '';
  $tag_names = isset($_POST['tag']) ? $_POST['tag'] : '';
  $author = isset($_POST['author']) ? $_POST['author'] : '';
  $image = isset($_POST['image']) ? $_POST['image'] : '';
  

    // Insert the post into the database  
    $query = "INSERT INTO posts (post_category_id, post_title, post_author, post_image, post_content, post_tags, post_status, post_date)
    VALUES (:category_id, :title, :author, :image, :content, :tag_names, 'draft', NOW())";

    $statement = $db->prepare($query);
    $statement->bindValue(':category_id', $category_id);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':author', $author);
    $statement->bindValue(':image', $image);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':tag_names', $tag_names);
    $result = $statement->execute();

    // Get the post id
    $post_id = $db->lastInsertId();

    // Redirect to the main page
    header("Location: index.php");
  }

?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Web3 Launchpad</title>
  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="../../public/css/bootstrap.min.css">
  <link rel="stylesheet/scss" href="../../public/css/_bootswatch.scss">
  <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
  <!-- Header -->
  <?php include 'header.php'; ?>
  <!-- End of Header -->

  <!-- Main Content -->
  <div class="container my-5">
    <h1 class="text-center">Create a New Blog Post</h1>
    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post"">
      <!-- Title -->
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" required>
      </div>

      <!-- Author -->
      <div class="form-group">
        <label for="author">Author</label>
        <input type="text" name="author" class="form-control" id="author" placeholder="Enter Author" required>
      </div>

      <!-- Category -->
      <div class="form-group">
        <label for="category">Category</label>
        <select name="category" class="form-control" id="category">
        <option value="1">TechBlogs</option>
          <option value="2">Tutorials</option>
          <option value="3">Courses</option>
          <option value="4">Web3News</option>
          <option value="5">JobsOpps</option>
          <option value="6">Web3Story</option>
        </select>
      </div>

      <!-- Tags -->
      <div class="form-group">
        <label for="tag">Tags </label>
        <input type="text" name="tag" class="form-control" id="tag" placeholder="Enter Tags" required>
      </div>

      <!-- Post_status -->
      <div class="form-group">
        <label for="post_status">Post Status</label>
        <select name="post_status" class="form-control" id="post_status">
          <option value="draft">Draft</option>
          <option value="published">Published</option>
        </select>
        
      <!-- Cover Image -->
      <div class="form-group">
        <label for="cover_image">Cover Image</label>
        <input type="file" name="cover_image" class="form-control-file" id="cover_image">
      </div> 

      <!-- Content -->
      <div class="form-group">
        <label for="content">Content</label>
        <textarea name="content" class="form-control" id="content" rows="10" required></textarea>
      </div>

      <button type="submit" name="submit" class="btn btn-primary">Create Post</button>
    </form>
  </div>
  <!-- End of Main Content -->

  <!-- Footer -->
  <?php include 'footer.php'; ?>
  <!-- End of Footer -->
</html>
