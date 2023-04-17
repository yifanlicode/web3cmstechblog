<?php
// Path: app/apis/common.php


if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if (isset($_SESSION['username'])) {
  $author = $_SESSION['username'];
} else {
  header("Location: login.php");
  exit;
}

require('includes/connect.php');
require('includes/ImageResize.php');
require('includes/ImageResizeException.php');

$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

//fetch the post from the database to be updated 
$query = "SELECT * FROM posts WHERE post_id = :id";
$stmt = $db->prepare($query);
$stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch();
$stmt->closeCursor();

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
  $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);
  $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
  $page_image = filter_input(INPUT_POST, 'page_image', FILTER_SANITIZE_STRING);


  // Check if delete image checkbox is checked
  $delete_image = isset($_POST['delete_image']);

  // Delete image from database and file system if checkbox is checked
  if ($delete_image) {
    $image_path = "uploads/" . $post['post_image'];
    unlink($image_path);

    $query = "
      UPDATE posts 
      SET post_image = NULL
      WHERE posts.post_id = :id
    ";

    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
  }

  // Check if a new image has been uploaded and delete the old image if it exists
  if (isset($_FILES['page_image']) && $_FILES['page_image']['error'] == 0) {
   
    //delete the old image
    $image_path = "uploads/" . $post['post_image'];
    if (file_exists($image_path)) {
      unlink($image_path);
    }

  // Upload and process the new image
  $image = $_FILES['page_image'];

    // Check if the uploaded file is an image
    $mime_type = mime_content_type($image['tmp_name']);
    if (in_array($mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {

// Resize and save the image
$filename = uniqid('', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
$upload_path = 'uploads/' . $filename;
$image_resize = new \Gumlet\ImageResize($image['tmp_name']);
$image_resize->resizeToWidth(700);
$image_resize->save($upload_path);

    // Update the post image in the database
    $query = "UPDATE posts SET post_image = :image WHERE post_id = :id";


    $stmt = $db->prepare($query);
    $stmt->bindValue(':image', $filename);
    $stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    $post_image = $image_name;

  } else {
    $error = "The uploaded file is not a valid image. Please upload a JPEG, PNG or GIF image.";
  }
}else {
  // Handle the case where no new image is uploaded
  $post_image = $post['post_image'];
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
post_author = :author
WHERE posts.post_id = :id";


    $stmt = $db->prepare($query);
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':category', $category);
    $stmt->bindValue(':content', $content);
    $stmt->bindValue(':tags', $tags);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':author', $author);
    $stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to the full blog page after updating the post
    header("Location: full_page.php");
    exit;
  }
}

// Delete the post from the database
if (isset($_POST['delete_post'])) {

  // Delete the image from the file system
  $image_path = "uploads/" . $post['post_image'];
  unlink($image_path);

  // Delete the post from the database
  $query = "DELETE FROM posts WHERE post_id = :id";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':id', $post_id, PDO::PARAM_INT); // Properly bind the value
  $stmt->execute();
  $stmt->closeCursor();

  // Redirect to the full blog page after deleting the post the same blog page
  header("Location: full_page.php");
  exit;
}

include 'includes/header.php';   // Path: app/apis/includes/header.php

?>

<!-- Main Content -->
<div class="container my-5">
  <h1>Edit Blog</h1>
  <form action="edit_page.php?id=<?= $post_id ?>" method="post" enctype="multipart/form-data">
    <!-- title -->
    <div class="form-group">
      <label for="title">Title:</label>
      <input type="text" class="form-control" name="title" id="title" value="<?= $post['post_title'] ?>">
    </div>

    <!-- category -->
    <div class="form-group">
      <label for="category">Category:</label>
      <!-- Replace with the following dropdown list -->
      <select name="category" class="form-control" id="category">
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


    <!-- 显示作者名 -->
    <div class="form-group">
      <label for="author">Author:</label>
      <input type="text" class="form-control" name="author" id="author" value="<?= $post['post_author'] ?>" disabled>
    </div>


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
      <input type="file" class="form-control-file" name="page_image" id="page_image">
    </div>

    <!-- Delete Image Checkbox -->
    <div class="form-group form-check">
      <input type="checkbox" class="form-check-input" id="delete_image" name="delete_image">
      <label class="form-check-label" for="delete_image">Delete Image</label>
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