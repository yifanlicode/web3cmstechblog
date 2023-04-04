<?php

/*******w******** 
    
    File Name: create.php
    Description: Create a new blog post and insert it into the database
    version: 1.2

****************/

// Include the database configuration file
require('authenticate.php');
require('connect.php');

//Gd library
require('ImageResize.php');
require('ImageResizeException.php');


// Check if the form is submitted

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the form data
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $category_id = isset($_POST['category']) ? $_POST['category'] : '';
  $tag_names = isset($_POST['tag']) ? $_POST['tag'] : '';
  $author = isset($_POST['author']) ? $_POST['author'] : '';

  $image = isset($_POST['image']) ? $_POST['image'] : '';
  $post_status = isset($_POST['post_status']) ? $_POST['post_status'] : '';
  

  // FILE UPLOAD CODE STARTS HERE
  // Define the upload path and  allowed file types 
  $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
  $upload_subfolder_name = 'uploads';
  $current_folder = dirname(__FILE__); // current folder


  //resize the image and upload it to the server
  if(isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
    // Get the  image file properties
    $image_filename = $_FILES['cover_image']['name']; 
    $temporary_image_path = $_FILES['cover_image']['tmp_name']; 
    $mime_type = mime_content_type($temporary_image_path);

    // Check if the file type is allowed
    if (in_array($mime_type, $allowed_mime_types)) {
      
      // Create the upload path
      // $upload_path_original = create_upload_path($image_filename, $upload_subfolder_name);
      $upload_path_original = 'uploads/' . $image_filename; 
      
      //move the image to the the upload folder
      move_uploaded_file($temporary_image_path, $upload_path_original);

       //create ImageResize object for resizing the image
      $image = new \Gumlet\ImageResize($upload_path_original);
      $image->resizeToWidth(400);
      $image->save($upload_path_original); //覆盖原图 


    } else {
      echo "Please upload a valid image file - jpeg, png, gif are allowed.";
    }
  } else{
    echo "If you don't upload an image, the default image will be used.";
  }
   
      // Insert the post into the database
      $query = "INSERT INTO posts (post_category_id, post_title, post_author, post_image, post_content, post_tags, post_status, post_date)
      VALUES (:category_id, :title, :author, :image, :content, :tag_names, :post_status, NOW())";


      // Insert the post tags into the database
      $post_id = $db->lastInsertId();
      $tag_names = explode(',', $tag_names);
      $statement = $db->prepare($query);
      $statement->bindValue(':category_id', $category_id);
      $statement->bindValue(':title', $title);
      $statement->bindValue(':author', $author);
      $statement->bindValue(':image', $upload_path_original);
      $statement->bindValue(':content', $content);
      $statement->bindValue(':tag_names', $tag_names);
      $statement->bindValue(':post_status', $post_status);
      $result = $statement->execute();


      // redirect to the view page
      header("Location: view.php");
      exit;

}

  // function create_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
  //   global $current_folder;
  //   $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
  //   return join(DIRECTORY_SEPARATOR, $path_segments);
  // }

?>

<?php include('header.php'); ?>

  <!-- Main Content -->
  <div class="container my-5">
    <h1 class="text-center">Create a New Blog Post</h1>
    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
      <!-- Title -->
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" required>
      </div>

      <!-- Author -->
      <div class="form-group">
        <label for="author">Author</label>
        <input type="text" name="author" class="form-control" id="author" placeholder="Enter Author" required>
      </div>

      <!-- Category -->
      <div class="form-group">
        <label for="category">Category</label>
        <select name="category" class="form-control" id="category">
          <option value="1">TechBlogs</option>
          <option value="2">Tutorials</option>
          <option value="3">Courses</option>
          <option value="4">Web3News</option>
          <option value="5">JobsOpps</option>
          <option value="6">Web3Story</option>
        </select>
      </div>

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
        
      <!-- Cover Image -->
      <div class="form-group">
        <label for="cover_image">Cover Image</label>
        <input type="file" name="cover_image" class="form-control-file" id="cover_image">
      </div> 

      <!-- Content -->
      <div class="form-group">
        <label for="content">Content</label>
        <textarea name="content" class="form-control" id="content" rows="10" required></textarea>
      </div>

      <button type="submit" name="submit" class="btn btn-primary">Create Post</button>
    </form>
  </div>
  <!-- End of Main Content -->

  <!-- Footer -->
  <?php include 'footer.php'; ?>
  <!-- End of Footer -->
