<?php
// Path: app/create_page.php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  $_SESSION['message'] = "Please login to create a new blog post.";
  header("Location: login.php");
  exit;
}

// Include the database connection  and GD library
require('includes/connect.php');
require('includes/ImageResize.php');
require('includes/ImageResizeException.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // POST DATE INTO DATABASE
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $tag_names = isset($_POST['tag']) ? $_POST['tag'] : '';
  $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
  $image = isset($_FILES['page_image']) ? $_FILES['page_image'] : null;
  $post_status = isset($_POST['post_status']) ? $_POST['post_status'] : '';
  $author = $_SESSION['username'];

  //category_id is 'new' if the user wants to add a new category
  if ($category_id == 'new') {
    $category_name = filter_input(INPUT_POST, 'new_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!empty($category_name)) {
      $statement = $db->prepare('INSERT INTO categories (cat_title) VALUES (:cat_title)');
      $statement->bindValue(':cat_title', $category_name);
      $statement->execute();
      $category_id = $db->lastInsertId();
    }
  }

  //tag_names is an array of tag names
  $tag_names = array_map('trim', explode(',', $tag_names));


  $query = "INSERT INTO posts(

    post_category_id,
    post_title,
    post_author,
    post_image,
    post_content,
    post_tags,
    post_status,
    post_date
  ) VALUES (
  
    :category_id,
    :title,
    :author,
    :image,
    :content,
    :tag_names,
    :post_status, 
    NOW()
  )";
  $statement = $db->prepare($query);
  $statement->bindValue(':category_id', $category_id);
  $statement->bindValue(':title', $title);
  $statement->bindValue(':author', $author);
  $statement->bindValue(':content', $content);
  $tag_names_str = implode(',', $tag_names);
  $statement->bindValue(':tag_names', $tag_names_str);
  $statement->bindValue(':post_status', $post_status);


  // Upload image if it's set
  if (!is_null($image)) {
    $mime_type = mime_content_type($image['tmp_name']);

    if (in_array($mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
      $filename = uniqid('', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
      $upload_path = 'uploads/' . $filename;

      try {
        $image_resize = new \Gumlet\ImageResize($image['tmp_name']);
        $image_resize->resizeToWidth(800);
        $image_resize->save($upload_path);
      } catch (\Gumlet\ImageResizeException $e) {
        error_log($e->getMessage());
      }
      $statement->bindValue(':image', $filename);
    }
  }

  if ($statement->execute()) {
    header('Location: full_page.php');
    exit;
  } else {
    echo 'Error creating post';
  }
  }

  // Get all categories
$statement = $db->query('SELECT * FROM categories');
$categories = $statement->fetchAll();
$statement->closeCursor();

include('includes/header.php');

?>



<!-- Main Content -->
<div class="container my-5">
  <h1 class="text-center">Create a New Blog Post</h1>
  <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
    <!-- Title -->
    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" required>
    </div>



    <!-- Category  -->
    <div class="form-group">
      <label for="category_id">Category</label>
      <select name="category_id" class="form-control" id="category">


        <?php foreach ($categories as $category) : ?>
          <option value="<?= $category['cat_id']; ?>"><?= $category['cat_title']; ?></option>
        <?php endforeach; ?>
        <option value="new" data-bs-toggle="collapse">Add a new category</option>
      </select>
    </div>


    <!-- New Category use bootstrap5 -->


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


      <!-- Upload Image -->
      <div class="form-group">
        <label for="page_image">Cover Image</label>
        <input type="file" name="page_image" class="form-control-file" id="page_image">
      </div>


      <!-- Content -->
      <div class="form-group">
        <label for="content">Content</label>
        <textarea name="content" class="form-control" id="content" rows="10" required></textarea>
      </div>


      <!-- author -->


      <div class="form-group">
        <label for="author" class="me-2">Author:</label>
        <p class="d-inline"><?= $_SESSION['username']; ?></p>
        <input type="hidden" name="author" value="<?= $_SESSION['username']; ?>">
      </div>


      <button type="submit" name="submit" class="btn btn-primary">Create Post</button>
  </form>
</div>
<!-- End of Main Content -->


<?php include('includes/footer.php'); ?>