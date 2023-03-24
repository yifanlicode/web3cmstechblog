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
$query = "SELECT Articles.*, Categories.name as category_name FROM Articles
          INNER JOIN Categories ON Articles.category_id = Categories.id
          WHERE Articles.id = :id";

$statement = $db->prepare($query);
$statement->bindValue(':id', $post_id);
$statement->execute();

$post = $statement->fetch();
$statement->closeCursor();

if ($post) {
  $postTitle = $post['title'];
  $postContent = $post['content'];
  $postCreateTime = $post['created_at'];
  $postUpdateTime = $post['updated_at'];
  $postCategory = $post['category_name'];
} else {
  $postTitle = "Post not found";
  $postContent = "Post not found";
  $postCreateTime = "Post not found";
  $postUpdateTime = "Post not found";
  $postCategory = "Post not found";
}


?>



<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>WWB <?= $post['title'] ?> </title>
  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Women in Web3 </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Explore</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Learn</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Build</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Jobs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="create.php">New Blog</a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- End of Header -->

  <!-- Main Content -->
    <div class="container my-5">
      <h1><?= $postTitle ?></h1>
      <p>Category:
        <?php
        // Get the category name
        $query = "SELECT name FROM Categories WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $post['category_id']);
        $statement->execute();
        $category = $statement->fetch();
        echo $category['name'];
        ?>
      </p>
      <p>Created at: <?= $postCreateTime ?></p>
      <p>Updated at: <?= $postUpdateTime ?></p>
      <p><?= $postContent ?></p>

      <p>
        <small><a href="edit.php?id=<?= $id; ?>">Edit</a></small>
      </p>
    </div>
    <!-- End of Main Content -->


  
      <!-- Footer -->
      <?php include 'footer.php'; ?>
      <!-- End of Footer -->

</html>