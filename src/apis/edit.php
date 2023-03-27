<?php

/*******
 *
 * This is the edit page for the blog
 * It allows logged in users to edit a blog post ( update or delete)
 ****************/


require('authenticate.php');
require('connect.php');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);


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

if (isset($_POST['update_post'])){

    // Get the post data from the form
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);

    try{  

      // Update the post in the database
      $query = "UPDATE Articles SET title = :title, content = :content, category_id = :category_id WHERE id = :id";
      $stmt = $db->prepare($query);
      $stmt->bindValue(':title', $title, PDO::PARAM_STR);
      $stmt->bindValue(':content', $content, PDO::PARAM_STR);
      $stmt->bindValue(':category_id', $category, PDO::PARAM_INT);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();} catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      }

  

    // Update the tags in the database
    try{
    $query = "DELETE FROM ArticleTags WHERE article_id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();} catch (Exception $e) {
      echo "Error: " . $e->getMessage();


    }

    $tags = explode(',', $tags);
    foreach ($tags as $tag) {
        $query = "INSERT INTO ArticleTags (article_id, tag_id) VALUES (:article_id, :tag_id)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':article_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':tag_id', $tag, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Redirect to the edit page
    header("Location: index.php");
    exit;
}

// If the form was deleted
// delete the post from the database

if (isset($_POST['delete_post'])){

    // Delete the post from the database
    $query = "DELETE FROM Articles WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to the home page
    header("Location: index.php");
    exit;

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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="../../public/css/style.css">
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
        
          <?php foreach ($categories as $category) : ?>
            <option value="<?= $category['id'] ?>" <?php if ($category['id'] == $post['category_id']) echo 'selected' ?>>
              <?= $category['name'] ?>
            </option>
          <?php endforeach; ?>

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
