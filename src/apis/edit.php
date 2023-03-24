<?php

/*******
 *
 * This is the edit page for the blog
 * It allows the user to edit a blog post
 ****************/

require('connect.php');
require('authenticate.php');

// UPDATE POST TO DATABASE
if (isset($_POST['update'])) {
  $post_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
  $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
  $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
  $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);

  $query = "UPDATE Articles SET title = :title, content = :content, category_id = :category_id, tags = :tags WHERE id = :id";
  $statement = $db->prepare($query);
  $statement->bindValue(':title', $title);
  $statement->bindValue(':content', $content);
  $statement->bindValue(':category_id', $category_id);
  $statement->bindValue(':tags', $tags);
  $statement->bindValue(':id', $post_id);
  $statement->execute();
  $statement->closeCursor();

  header("Location: index.php");
  exit;
}

// DELETE POST FROM DATABASE
if (isset($_POST['delete'])) {
  $post_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $query = "DELETE FROM Articles WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $post_id);
    $statement->execute();
    $statement->closeCursor();

    header("Location: index.php");
    exit;
}
// GET POST FROM DATABASE
$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Get the post from the database
$query = "SELECT * FROM Articles WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindValue(':id', $post_id);
$statement->execute();
$post = $statement->fetch();
$statement->closeCursor();

//Get the categories from the database
$query = "SELECT * FROM Categories";
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll();
$statement->closeCursor();

// Check if the post exists
if (!$post) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title> Edit this POST! </title>
</head>
<body>

  <!-- Header -->
  <?php include 'header.php'; ?>
  <!-- End of Header -->


<!-- Main Content -->
<div class="container my-5">
  <h1>Edit Blog</h1>
  <form action="edit.php" method="post">
    <div class="form-group">
      <label for="title">Title:</label>
      <input type="text" class="form-control" name="title" id="title" value="<?= $post['title'] ?>">
    </div>
    <div class="form-group">
      <label for="category">Category:</label>
      <select name="category" id="category" class="form-control">
        <?php
        foreach ($categories as $category) {
          if ($post['category_id'] == $category['id']) {
            echo "<option value='" . $category['id'] . "' selected>" . $category['name'] . "</option>";
          } else {
            echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
          }
        }
        ?>
      </select>
    </div>
    <div class="form-group">
      <label for="content">Content:</label>
      <textarea name="content" id="content" class="form-control" rows="10"><?= $post['content'] ?></textarea>
    </div>
    <div class="form-group">
      <input type="hidden" name="id" value="<?= $post['id'] ?>">
      <input type="submit" class="btn btn-primary" name="update" value="Update">
      <input type="submit" class="btn btn-danger" name="delete" value="Delete">
    </div>
  </form>
</div>
<!-- End of Main Content -->


      <!-- Footer -->
      <?php include 'footer.php'; ?>
      <!-- End of Footer -->
</body>
</html>

