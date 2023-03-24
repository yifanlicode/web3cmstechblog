<?php

/*******
 *
 * This is the edit page for the blog
 * It allows the user to edit a blog post
 ****************/

require('authenticate.php');
require('connect.php');


// GET POST FROM DATABASE
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Get the post from the database
$query = "SELECT Articles.*, Categories.name as category_name FROM Articles
          INNER JOIN Categories ON Articles.category_id = Categories.id
          WHERE Articles.id = :id";

$statement = $db->prepare($query);
$statement->bindValue(':id', $id);
$statement->execute();
$post = $statement->fetch();
$statement->closeCursor();

if (!$post) {
  header("Location: index.php");
  exit;
}

//Get the categories from the database
$query = "SELECT * FROM Categories";
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll();
$statement->closeCursor();

$query = "SELECT Tags.name FROM ArticleTags
          INNER JOIN Tags ON ArticleTags.tag_id = Tags.id
          WHERE ArticleTags.article_id = :id";
$statement = $db->prepare($query);
$statement->bindValue(':id', $id);
$statement->execute();
$tags = $statement->fetchAll();
$statement->closeCursor();

$tags = implode(',', array_map(function ($tag) {
  return $tag['name'];
}, $tags));

// UPDATE POST TO DATABASE
if (isset($_POST['update_post'])) {

  $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
  $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
  $category_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
  $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);

  // Update the post
  $query = "UPDATE Articles SET title = :title, content = :content, category_id = :category_id WHERE id = :id";

  $statement = $db->prepare($query);
  $statement->bindValue(':title', $title);
  $statement->bindValue(':content', $content);
  $statement->bindValue(':category_id', $category_id);
  $statement->bindValue(':id', $id);
  $statement->execute();

  // Delete the existing tags for the post
  $query = "DELETE FROM ArticleTags WHERE article_id = :id";
  $statement = $db->prepare($query);
  $statement->bindValue(':id', $id);
  $statement->execute();

  // Add the new tags
  $tags = explode(',', $tags);

  foreach ($tags as $tag) {
    $query = "SELECT id FROM Tags WHERE name = :name";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $tag);
    $statement->execute();
    $tag_id = $statement->fetchColumn();

    if (!$tag_id) {
      $query = "INSERT INTO Tags (name) VALUES (:name)";
      $statement = $db->prepare($query);
      $statement->bindValue(':name', $tag);
      $statement->execute();
      $tag_id = $db->lastInsertId();
    }

    $query = "INSERT INTO ArticleTags (article_id, tag_id) VALUES (:article_id, :tag_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':article_id', $id);
    $statement->bindValue(':tag_id', $tag_id);
    $statement->execute();
  }

  header("Location: index.php");
  exit;
}


// DELETE POST FROM DATABASE
if (isset($_POST['delete_post'])) {

  $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

  // Delete article tags
  $query = "DELETE FROM ArticleTags WHERE article_id = :id";
  $statement = $db->prepare($query);
  $statement->bindValue(':id', $id);
  $statement->execute();

  // Delete the post
  $query = "DELETE FROM Articles WHERE id = :id";
  $statement = $db->prepare($query);
  $statement->bindValue(':id', $id);
  $statement->execute();

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
  <title> Edit the Blog </title>
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
        <label for="tags">Tags:</label>
        <input type="text" class="form-control" name="tags" id="tags" value="<?= $tags ?>">
      </div>

      <div class="form-group">
        <label for="content">Content:</label>
        <textarea name="content" id="content" class="form-control" rows="10"><?= $post['content'] ?></textarea>
      </div>
      <div class="form-group">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <input type="submit" class="btn btn-primary" name="update_post" value="Update">
        <input type="submit" class="btn btn-danger" name="delete_post" value="Delete">
      </div>
    </form>
  </div>
  <!-- End of Main Content -->


  <!-- Footer -->
  <?php include 'footer.php'; ?>
  <!-- End of Footer -->
</body>

</html>