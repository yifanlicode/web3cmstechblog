<?php

//path: app/create_page.php

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

   //can not get the content from the form why??
  $content = $_POST['content'];
  $tag_names = isset($_POST['tag']) ? $_POST['tag'] : '';
  $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
  $image = isset($_FILES['page_image']) ? $_FILES['page_image'] : null;
  $post_status = isset($_POST['post_status']) ? $_POST['post_status'] : '';
  $author = $_SESSION['username'];


  // Create a new category if the user entered a new category name in the form field 
  if ($category_id == 'new') {
    $category_name = filter_input(INPUT_POST, 'new_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!empty($category_name)) {
      $statement = $db->prepare('INSERT INTO categories (cat_title) VALUES (:cat_title)');
      $statement->bindValue(':cat_title', $category_name);
      $statement->execute();
      $category_id = $db->lastInsertId();
    }
  }

  // Create a new tag if the user entered a new tag name in the form field
  $tag_names = array_map('trim', explode(',', $tag_names));


  // Print out the values for debugging 
  echo "Content: " . $content . "<br>";
  echo "Title: " . $title . "<br>";
  echo "Category ID: " . $category_id . "<br>";
  echo "Post Status: " . $post_status . "<br>";

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
      }
    }
    if (!empty($filename)) {
      $statement->bindValue(':image', $filename);
    } else {
      $statement->bindValue(':image', null, PDO::PARAM_NULL);
    }
  

  if ($statement->execute()) {
    // header('Location: index.php'); 
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
  <form action="create_page.php" method="post" enctype="multipart/form-data">


    <!-- Title -->
    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" required>
    </div>

    <!-- Category  -->
    <div class="form-group">
      <label for="category_id">Category</label>
      <select name="category_id" class="form-control" id="category" onchange="showNewCategoryField(this.value);">
        <?php foreach ($categories as $category) : ?>
          <option value="<?= $category['cat_id']; ?>"><?= $category['cat_title']; ?></option>
        <?php endforeach; ?>
        <option value="new">Add a new category</option>
      </select>
    </div>

    <div class="form-group collapse" id="new_category_div">
      <label for="new_category">New Category Name</label>
      <input type="text" name="new_category" class="form-control" id="new_category" placeholder="Enter New Category Name">
    </div>

    <!-- Tags -->
    <div class="form-group">
      <label for="tag">Tags </label>
      <input type="text" name="tag" class="form-control" id="tag" placeholder="Enter Tags">
    </div>

    <!-- Author -->
    <div class="form-group">
      <label for="author">Author:</label>
      <input type="text" class="form-control" name="author" id="author" value="<?= $_SESSION['username']; ?>" disabled>
    </div>

    <!-- Post_status -->
    <div class="form-group">
      <label for="post_status">Post Status</label>
      <select name="post_status" class="form-control" id="post_status">
        <option value="draft">Draft</option>
        <option value="published">Published</option>
      </select>
    </div>


    <!-- Upload Image -->
    <div class="form-group">
      <label for="page_image">Cover Image</label>
      <input type="file" name="page_image" class="form-control-file" id="page_image">
    </div>


      <!-- Content -->
      <div class="form-group">
        <!-- create quil #editor  -->
        <div id="editor" style="height: 500px;">
           <p>Hello World!</p> 
        </div>
        <!-- hidden input to store the content -->
        <textarea name="content" class="form-control" id="content" style="display:none;"></textarea>
      </div>

      <!-- Submit -->
      <button type="submit" name="submit" id="submit" class="btn btn-primary" >Create Post</button>
  </form>
</div>
<!-- End of Main Content -->


<script>
        const quill = new Quill('#editor', {
          theme: 'snow'
        });

        document.querySelector("form").addEventListener("submit", function(event) {
        event.preventDefault();
        var content = quill.root.innerHTML;
        document.getElementById("content").value = content;
        document.querySelector("form").submit();
});
      </script>

<?php include('includes/footer.php'); ?>