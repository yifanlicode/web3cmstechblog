<?php

//path: app/create_page.php

// Start the session
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  $_SESSION['message'] = "Please login to create a new blog post.";
  header("Location: login.php");
  exit;
}

require('includes/connect.php');
require('includes/ImageResize.php');
require('includes/ImageResizeException.php');


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // POST DATE INTO DATABASE
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_STRING);
  $category_name = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $content = $_POST['content'];
  $tag_names = isset($_POST['tag']) ? $_POST['tag'] : '';
  $image = isset($_FILES['page_image']) ? $_FILES['page_image'] : null;
  $post_status = isset($_POST['post_status']) ? $_POST['post_status'] : '';
  $author = $_SESSION['username'];


  if (!empty($category_name)) {
    // Category name provided, check if it already exists in the database
    $statement = $db->prepare('SELECT cat_id FROM categories WHERE cat_title = :cat_title');
    $statement->bindValue(':cat_title', $category_name);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    if ($result) { // Category already exists, use its ID
      $category_id = $result['cat_id']; // Get the category ID
      echo "Category ID: " . $category_id . "<br>";
    } else {
      echo "Category ID: " . $category_id . "<br>";
      $statement = $db->prepare('INSERT INTO categories (cat_title, created_at, updated_at) VALUES (:cat_title, NOW(), NOW())
        ');
      $statement->bindValue(':cat_title', $category_name);
      $statement->execute();
      $category_id = $db->lastInsertId();
    }
  } else {
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
  }


  // Create a new tag if the user entered a new tag name in the form field
  // $tag_names = array_map('trim', explode(',', $tag_names));
  $tag_names = isset($_POST['tag']) ? explode(',', $_POST['tag']) : [];


  // Insert the post into the database
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


  // Upload image
  if (!is_null($image) && !empty($image['tmp_name'])) {
    $mime_type = mime_content_type($image['tmp_name']);
    if (in_array($mime_type, ['image/jpeg', 'image/png', 'image/jpg'])) {
      $filename = uniqid('', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
      $upload_path = 'uploads/' . $filename;

      try {
        $image_resize = new \Gumlet\ImageResize($image['tmp_name']);
        $image_resize->resizeToWidth(800);
        $image_resize->save($upload_path);
      } catch (\Gumlet\ImageResizeException $e) {
        error_log($e->getMessage());
      }
    }
  }
  if (!empty($filename)) {
    $statement->bindValue(':image', $filename);
  } else {
    $statement->bindValue(':image', null, PDO::PARAM_NULL);
  }

  if ($statement->execute()) {
    $post_id = $db->lastInsertId();
    header('Location: full_page.php?id=' . $post_id);
    exit;
  } else {
    header('Location: create_page.php');
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
  <h3 class="text-center">Create a New Post</h3>
  <form action="create_page.php" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-9 offset-md-2">
        <div class="card">
          <div class="card-body">
            <!-- Title -->
            <div class="form-group mb-3">
              <div class="input-group align-items-center">
                <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" required>
              </div>
            </div>

            <!-- Author -->
            <div class="form-group mb-3">
              <div class="input-group align-items-center">
                <input type="text" class="form-control" name="author" id="author" value="<?= $_SESSION['username']; ?>" disabled>
              </div>
            </div>

            <!-- Post_status -->
            <div class="form-group mb-3">
              <div class="input-group">
                <div class="dropdown w-100">
                  <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="postStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Post Status
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="postStatusDropdown" style="width: 100%;">
                    <li><a class="dropdown-item" href="#" data-value="draft">Draft</a></li>
                    <li><a class="dropdown-item" href="#" data-value="published">Published</a></li>
                  </ul>
                  <input type="hidden" name="post_status" id="post_status">
                </div>
              </div>
            </div>


            <!-- Category  -->
            <div class="form-group mb-3">
              <div class="input-group">
                <div class="dropdown w-100">
                  <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Choose or Add Category
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="categoryDropdown" style="width: 100%;">
                    <?php foreach ($categories as $category) : ?>
                      <li><a class="dropdown-item" href="#" data-value="<?= $category['cat_id']; ?>"><?= $category['cat_title']; ?></a></li>
                    <?php endforeach; ?>
                    <li><a class="dropdown-item" href="#" data-value="new">Add a new category</a></li>
                  </ul>
                  <input type="hidden" name="category_id" id="category_id">
                </div>
              </div>
            </div>


            <!-- New Category -->
            <div class="form-group mb-3" id="new_category_group" style="display:none;">
              <div class="input-group">
                <input type="text" name="category_name" class="form-control" id="new_category" placeholder="Enter New Category">
              </div>
            </div>

            <!-- Tags -->
            <div class="form-group mb-3">
              <div class="input-group">
                <input type="hidden" name="tag" id="hidden-tag-input">
                <input type="text" class="form-control" id="tag-input" placeholder="Enter tags">
              </div>
              <div id="tag-list" class="mt-2"></div>
            </div>

            <!-- Upload Image -->
            <div class="form-group mb-3">
              <div class="input-group">
                <input type="file" name="page_image" class="form-control-file d-none" id="page_image">
                <input type="text" class="form-control" readonly id="page_image_label" placeholder="Upload a cover image (JPG, PNG, or GIF)">
                <button class="btn btn-outline-primary" type="button" onclick="document.getElementById('page_image').click()">Upload</button>
              </div>
            </div>


            <!-- Content -->
            <div class="form-group mb-3">
              <textarea name="content" id="content" style="height: 500px;"></textarea>
            </div>
            <script>
              // tineMCE editor 
              tinymce.init({
                selector: '#content',
                menubar: false,
                toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image',
                plugins: 'link image code',
                default_link_target: '_blank',
                image_dimensions: false,
                image_uploadtab: false,
                image_class_list: false,
                content_style: 'body {font-family: Arial,Helvetica,sans-serif; font-size: 16px; line-height: 1.6;}',
              });
            </script>


            <!-- Submit -->
            <button type="submit" name="submit" id="submit" class="btn btn-primary">Create Post</button>
  </form>
</div>
</div>
</div>
</div>
</div>


<!-- End of Main Content -->

<?php include('includes/footer.php'); ?>