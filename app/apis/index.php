<?php

/*******
 *
 * This is the main page for the website
 ****************/

require(__DIR__ . '/connect.php');
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

include('header.php');

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
  <?php include 'sidebar.php'; ?>
  <!-- End of sidebar -->

  </div>
</div>
<!-- End of Main Content -->


  <!-- Footer -->
  <?php include 'footer.php'; ?>
  <!-- End of Footer -->

  