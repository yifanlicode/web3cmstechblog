<?php include "includes/admin_header.php"; ?>

<!-- Page content -->
<div class="container my-4" style="min-height: calc(95vh - 120px);">
  <div class="row">
    <!-- Dashboard navigation -->
    <div class="col-lg-3">
      <div class="list-group">
        <a href="users.php" class="list-group-item list-group-item-action">Users</a>
        <a href="posts.php" class="list-group-item list-group-item-action">Posts</a>
        <a href="categories.php" class="list-group-item list-group-item-action">Categories</a>
        <a href="comments.php" class="list-group-item list-group-item-action">Comments</a>
      </div>
    </div>

    <!-- Dashboard content -->
    <div class="col-lg-9">
      <div class="card">
        <div class="card-header">
          <h4>Welcome to the Admin Panel</h4>
        </div>
        <div class="card-body">
          <p class="card-text">This is the admin panel where you can manage users, posts, categories, and comments. Use the navigation links on the left to access different sections.</p>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include "includes/admin_footer.php"; ?>
