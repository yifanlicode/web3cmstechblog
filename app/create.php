<?php
//description: create a new post
//path: app/apis/create.php

require('authenticate.php');
require('connect.php');
require('ImageResize.php');
require('ImageResizeException.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the form data
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $tag_names = isset($_POST['tag']) ? $_POST['tag'] : '';
  $author = isset($_POST['author']) ? $_POST['author'] : '';
  $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
  $image = isset($_FILES['cover_image']) ? $_FILES['cover_image'] : null;
  $post_status = isset($_POST['post_status']) ? $_POST['post_status'] : '';


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
  //if the user wants to add a new tag, it will be in the array
  $tag_names = array_map('trim', explode(',', $tag_names));

// Insert the post into the database
$query = "INSERT INTO posts (post_category_id, post_title, post_author, post_image, post_content, post_tags, post_status, post_date)
VALUES (:category_id, :title, :author, :image, :content, :tag_names, :post_status, NOW())";

$statement = $db->prepare($query);
$statement->bindValue(':category_id', $category_id);
$statement->bindValue(':title', $title);
$statement->bindValue(':author', $author);
$statement->bindValue(':content', $content);
$tag_names_str = implode(',', $tag_names);
$statement->bindValue(':tag_names', $tag_names_str);
$statement->bindValue(':post_status', $post_status);


// FILE UPLOAD CODE STARTS HERE
if(!is_null($image)) {
  $mime_type = mime_content_type($image['tmp_name']);

  if(in_array($mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
    $filename = uniqid('', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
    $upload_path = 'uploads/' . $filename;
   
    try{
      $image_resize = new \Gumlet\ImageResize($image['tmp_name']);
      $image_resize->resizeToWidth(300);
      $image_resize->save($upload_path);
    } catch(\Gumlet\ImageResizeException $e) {
      error_log($e->getMessage());
    }
    $statement->bindValue(':image', $upload_path);
  }
}
if ($statement->execute()) {
  header('Location: view.php');
  exit;
} else {
  echo 'Error creating post';
}
}

$statement = $db->query('SELECT * FROM categories');
$categories = $statement->fetchAll();
$statement->closeCursor();


?>

<?php include('includes/header.php'); ?>

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
<footer class="bg-light py-3">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <p class="mb-0 text-center text-lg-start">© 2023 Web3 Launchpad</p>
      </div>
      <div class="col-lg-4">
        <h5 class="mb-3 text-center">Subscribe to Our Newsletter</h5>
        <form class="subscribe-form d-flex flex-row">
          <div class="form-group">
            <input type="email" class="form-control me-2" placeholder="Enter your email address">
          </div>
          <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>
      </div>
      <div class="col-lg-4">
        <h5 class="mb-3 text-center">Follow Us</h5>
        <ul class="list-unstyled d-flex justify-content-center">
          <li class="me-4">
            <a href="https://twitter.com/" target="_blank">
            Twitter <i class="fab fa-twitter fa-2x"></i>
            </a>
          </li>
          <li class="me-4">
            <a href="https://github.com/" target="_blank">
             Github <i class="fab fa-github fa-2x"></i>
            </a>
          </li>
          <li class="me-4">
            <a href="https://discord.com/" target="_blank">
            Discord  <i class="fab fa-discord fa-2x"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!-- JS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2.10.2/dist/esm/popper.min.js"></script>

<!-- Sort posts -->
<script>
  function sortPosts(sortType) {
    const encodedSortType = encodeURIComponent(sortType);
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
        const blogListContainer = document.getElementById('blog-list-container');
        blogListContainer.innerHTML = this.responseText;
      }
    };
    xhr.open('GET', `blog-list.php?sort=${encodedSortType}`, true);
    xhr.send();
  }
</script>

<script>
  const categorySelect = document.getElementById('category');
  const newCategoryDiv = document.getElementById('new-category');

  categorySelect.addEventListener('change', function() {
    if (categorySelect.value === 'new') {
      newCategoryDiv.classList.add('show');
    } else {
      newCategoryDiv.classList.remove('show');
    }
  });
</script>

</body>
</html>