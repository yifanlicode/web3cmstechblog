<?php

require('authenticate.php');
require('connect.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the form data
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $content =  filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $category_id = isset($_POST['category']) ? $_POST['category'] : '';
  $tag_ids = isset($_POST['tag']) ? $_POST['tag'] : '';

  // Validate the form data
  if (empty($title) || empty($content)) {
    $error = "All fields are required";
  } else {
    // Insert the post into the database  
    $query = "INSERT INTO Articles (title, content, cover_image, category_id, user_id, created_at, updated_at)
    VALUES (:title, :content, '', :category_id, 1, NOW(), NOW())";

    $statement = $db->prepare($query);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':category_id', $category_id);
    $result = $statement->execute();

// Get the post id
$post_id = $db->lastInsertId();

// Insert the tags into the ArticleTags table
$tag_ids = explode(",", $tag_ids);
foreach ($tag_ids as $tag_name) {
  // Check if the tag exists in the Tags table
  $query = "SELECT id FROM Tags WHERE name = :name";
  $statement = $db->prepare($query);
  $statement->bindValue(':name', $tag_name);
  $statement->execute();
  $result = $statement->fetch();

  // If the tag doesn't exist, insert it
  if (!$result) {
    $query = "INSERT INTO Tags (name, created_at, updated_at) VALUES (:name, NOW(), NOW())";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $tag_name);
    $statement->execute();

    $tag_id = $db->lastInsertId();
  } else {
    $tag_id = $result['id'];
  }

  // Insert the relationship between the article and the tag
  $query = "INSERT INTO ArticleTags (article_id, tag_id, created_at, updated_at)
            VALUES (:article_id, :tag_id, NOW(), NOW())";

  $statement = $db->prepare($query);
  $statement->bindValue(':article_id', $post_id);
  $statement->bindValue(':tag_id', $tag_id);
  $result = $statement->execute();
}

// Redirect to the main page
header("Location: index.php");
  }
}

?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Women in Web3 Blog</title>
  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>
    <!-- Header -->
    <?php include 'header.php'; ?>
    <!-- End of Header -->
  <!-- Main Content -->
  `<div class="container my-5">
    <h1 class="text-center">Create a New Blog Post</h1>
    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post"">
        <!-- Title -->
        <div class=" form-group">
      <label for="title">Title</label>
      <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" required>
  </div>
  <!-- Category -->
  <div class="form-group">
    <label for="category">Category</label>
    <select name="category" class="form-control" id="category" required>
      <option value="">Select Category</option>
      <option value="1">Web3</option>
      <option value="2">Blockchain</option>
      <option value="3">Cryptocurrency</option>
      <option value="4">Women in Tech</option>
    </select>
  </div>
  <!-- Tag -->
  <div class="form-group">
    <label for="tag">Tag</label>
    <input type="text" name="tag" class="form-control" id="tag" placeholder="Enter Tag (separated by comma)" required>
  </div>
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

 
      <!-- Footer -->
      <?php include 'footer.php'; ?>
      <!-- End of Footer -->

</html>