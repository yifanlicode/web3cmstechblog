<?php

/*******
 *
 * This is the main page for the website
 * home page
 ****************/

if (!isset($_SESSION)) {
  session_start();
}

require 'includes/connect.php';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
include('includes/header.php');

?>

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
      <?php if (isset($_SESSION['user_id'])) : ?>
        <div class="d-flex justify-content-end mb-3 align-items-center">
          <label class="me-2 fw-bold" for="sort-by-select">Sort by:</label>
          <select class="form-select-sm rounded-pill border-0" onchange="sortPosts(this.value)">
            <option value="post_views_count" <?php if ($sort == '' || $sort == 'post_views_count') echo 'selected' ?>>Views</option>
            <option value="post_title" <?php if ($sort == 'post_title') echo 'selected' ?>>Title</option>
            <option value="post_date" <?php if ($sort == 'post_date') echo 'selected' ?>>Post Date</option>
            <option value="update_date" <?php if ($sort == 'update_date') echo 'selected' ?>>Update Date</option>
          </select>
        </div>
      <?php endif; ?>

    
      <!-- Include blog-list.php -->
      <div id="blog-list-container">
        <?php include 'includes/blog-list.php'; ?>
      </div>
    </div>

    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>
    <!-- End of sidebar -->

  </div>
</div>
<!-- End of Main Content -->


<!-- Footer -->
<?php include 'includes/footer.php'; ?>
<!-- End of Footer -->