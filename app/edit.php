<?php
// Path: app/apis/common.php

require('authenticate.php');
require('connect.php');


// Get the post ID from the URL
$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

//fetch the post from the database
$query = "SELECT * FROM posts WHERE post_id = :id";
$stmt = $db->prepare($query);
$stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch();
$stmt->closeCursor();

if (!$post) {
  echo "Post not found!";
  exit;
}


// Fetch categories for the dropdown
$query = "SELECT * FROM categories";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll();
$stmt->closeCursor();


// UPDATE POST TO DATABASE

if (isset($_POST['update_post'])) {
  // Get the form data
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
  $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
  $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
  $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
  $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);
  $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
  $image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);

  //handle image upload

  if (!empty($_FILES['image']['tmp_name'])) {
    $image_name = basename($_FILES['image']['name']);
    $image_path = "../../public/images/" . $image_name;
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
  } else {
    $image_name = $post['post_image'];
  }


  //title and content ,category can not be empty
  if (empty($title) || empty($content) || empty($category)) {
    $error = "Please fill in the required fields: Title, Content, Category";
  } else {
    // Update the post in the database
    $query = "
    UPDATE posts 
    SET post_title = :title, 
    post_category_id = :category, 
    post_content = :content, 
    post_tags = :tags, 
    post_status = :status,
    post_author = :author,
    post_image = :image
    WHERE posts.post_id = :id";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':category', $category);
    $stmt->bindValue(':content', $content);
    $stmt->bindValue(':tags', $tags);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':author', $author);
    $stmt->bindValue(':image', $image);
    $stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
    $stmt->execute();


    // Redirect to the edit page
    header("Location: edit.php?id=$post_id");
    exit;
  }
}


// Delete the post from the database

if (isset($_POST['delete_post'])) {
  $query = "DELETE FROM posts WHERE post_id = :id";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':id', $post_id, PDO::PARAM_INT); // Properly bind the value
  $stmt->execute();
  $stmt->closeCursor();

  // Redirect to the index page
  header("Location: index.php");
  exit;
}

include 'includes/header.php';   // Path: app/apis/includes/header.php

?>

  <!-- Main Content -->
  <div class="container my-5">
    <h1>Edit Blog</h1>
    <form action="edit.php?id=<?= $post_id ?>" method="post">
      <!-- title -->
      <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" name="title" id="title" value="<?= $post['post_title'] ?>">
      </div>

      <!-- category -->
      <div class="form-group">
        <label for="category">Category:</label>
        <!-- Replace with the following dropdown list -->
        <select name="category">
          <?php foreach ($categories as $cat) : ?>
            <option value="<?php echo $cat['cat_id']; ?>" <?php echo ($cat['cat_id'] == $post['post_category_id']) ? 'selected' : ''; ?>>
              <?php echo $cat['cat_title']; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Tags -->
      <div class="form-group">
        <label for="tags">Tags:</label>
        <input type="text" class="form-control" name="tags" id="tags" value="<?= $post['post_tags'] ?>">
      </div>

      <div class="form-group">
        <label for="author">Author:</label>
        <input type="text" class="form-control" name="author" id="author" value="<?= $post['post_author'] ?>">
      </div>


      <!-- Status -->
      <!-- Status input field -->
      <div class="form-group">
        <label for="status">Status:</label>
        <select name="status" id="status" class="form-control">
          <option value="draft" <?php echo ($post['post_status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
          <option value="published" <?php echo ($post['post_status'] == 'published') ? 'selected' : ''; ?>>Published</option>
        </select>
      </div>

      <!-- Image Upload-->
      <div class="form-group">
        <label for="image">Image:</label>
        <input type="file" class="form-control-file" name="image" id="image" accept="image/*">
      </div>


      <!-- Content -->
      <div class="form-group">
        <label for="content">Content:</label>
        <textarea name="content" id="content" class="form-control" rows="10"><?= $post['post_content'] ?></textarea>
      </div>

      <div class="form-group">
        <input type="hidden" name="id" value="<?= $post['post_id'] ?>">
        <input type="submit" class="btn btn-primary" name="update_post" value="Update" onclick="return confirm('ARE YOU SURE TO UPDATE THIS BLOG?')">
        <input type="submit" class="btn btn-danger" name="delete_post" value="Delete" onclick="return confirm('ARE YOU SURE TO DELETE THIS BLOG?')">
      </div>
    </form>
  </div>
  <!-- End of Main Content -->


  <!-- Footer -->
  <?php include 'includes/footer.php'; ?>
  <!-- End of Footer -->