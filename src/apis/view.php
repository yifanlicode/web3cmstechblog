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
  <h1><?= $postTitle ?></h1>
  <p class="fw-bold">Category:
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
  <p class="fw-bold">Created at: <?= $postCreateTime ?></p>
  <p class="fw-bold">Updated at: <?= $postUpdateTime ?></p>
  <p><?= $postContent ?></p>
  <p>
    <small><a href="edit.php?id=<?= $post_id; ?>">Edit</a></small>
  </p>
</div>
  <!-- End of Main Content -->

  <!-- Footer -->
  <?php include 'footer.php'; ?>
  <!-- End of Footer -->

</html>