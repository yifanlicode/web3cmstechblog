<?php

/*******
 *
 * This is the main page for the website
 ****************/

require(__DIR__ . '/connect.php');
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

?>



<!DOCTYPE html>
<html>
<head>
  <!-- SEO  -->
  <meta name="description" content="Web3 Launchpad is a one-stop destination for anyone looking to learn and work in the Web3 and blockchain industry.">
  <meta name="keywords" content="Web3, Blockchain, Dev, Learning, Tutorials">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Web3 Launchpad</title>
  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="../../public/css/bootstrap.min.css">
  <link rel="stylesheet/scss" href="../../public/css/_bootswatch.scss">
  <link rel="stylesheet" href="../../public/css/style.css">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-ai3DlHwZ5K5W5GTHYYXcBbXEikRyvCtWXBx8Hg+PpDEYw+ZRMHnZa8nXQ2viM59JScfzsFbSVn71MZ0kgQGbQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

  <!-- Header -->
  <?php include(__DIR__ . '/header.php'); ?>
  <!-- End of Header -->

 <!-- Main Content -->
 <div class="container-fluid my-5">
 <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 text-center">
    <h1 class="display-4 mb-4 text-center" style="white-space: nowrap;">Welcome to Web3 Launchpad</h1>
      <p class="lead mb-4">Blast off into the Web3 universe with Web3 Launchpad</p>
      <hr class="my-4">
    </div>
  </div>


  <div class="row">
    <div class="col-lg-9">
        <!-- Sort by dropdown menu -->
  <div class="d-flex justify-content-end mb-3">
      <label class="me-2" for="sort-by-select">Sort by:</label>
      <select id="sort-by-select" onchange="sortPosts(this.value)">
        <option value="post_views_count" <?php if ($sort == '' || $sort == 'post_views_count') echo 'selected' ?>>Views</option>
        <option value="post_date" <?php if ($sort == 'post_date') echo 'selected' ?>>Post Date</option>
        <option value="update_date" <?php if ($sort == 'update_date') echo 'selected' ?>>Update Date</option>
      </select>
    </div>


  <!-- Include blog-list.php -->
  <div id="blog-list-container">
        <?php include 'blog-list.php'; ?>
      </div>
    </div>

  <!-- Sidebar -->
  <div class="col-lg-3">
    <div class="sidebar">
    
      <!-- Tags cloud -->
      <!-- Include tags-cloud.php -->
      <?php include 'tags-cloud.php'; ?>

      <!-- Categories -->
      <!-- Include categories.php -->
      <?php include 'categories.php'; ?>
      

      <!-- Popular articles -->
      <!-- Include popular-posts.php -->
      <?php include 'popular-posts.php'; ?>

    </div>
  </div>
  <!-- End of sidebar -->

  </div>
</div>
<!-- End of Main Content -->


  <!-- Footer -->
  <?php include 'footer.php'; ?>
  <!-- End of Footer -->

  <!-- JS Scripts -->
  
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

</body>
</html>