<?php

if (!isset($_SESSION)) {
  session_start();
}

// check if user logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}
require 'includes/connect.php';

// Delete post if requested
if (isset($_POST["delete_post_id"])) {
  $post_id = filter_input(INPUT_POST, "delete_post_id", FILTER_VALIDATE_INT);
  if ($post_id) {
    $query = "DELETE FROM posts WHERE post_id = :post_id";
    $statement = $db->prepare($query);
    $statement->bindParam(":post_id", $post_id);
    $statement->execute();
    $statement->closeCursor();
    $_SESSION["message"] = "Post deleted successfully";
    header("Location: posts.php");
    exit();
  }
}

// Fetch all posts
$query = "SELECT * FROM posts ORDER BY post_id DESC";
$statement = $db->prepare($query);
$statement->execute();
$posts = $statement->fetchAll();
$statement->closeCursor();


include "includes/admin_header.php";


?>


<div class="col-lg-3">
  <div class="list-group">
    <a href="user.php" class="list-group-item list-group-item-action">Users</a>
    <a href="posts.php" class="list-group-item list-group-item-action active">Posts</a>
    <a href="categories.php" class="list-group-item list-group-item-action ">Categories</a>
    <a href="comments.php" class="list-group-item list-group-item-action">Comments</a>
  </div>
</div>

<div class="col-lg-9">

  <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">


      <!-- Begin Page Content -->
      <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">All Posts</h1>

        <?php if (isset($_SESSION["message"])) : ?>
          <div class="alert alert-success">
            <?php echo $_SESSION["message"]; ?>
          </div>
          <?php unset($_SESSION["message"]); ?>
        <?php endif; ?>

        <div class="row">
          <div class="col-md-12">
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Posts Table</h6>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($posts as $post) : ?>
                        <tr>
                          <td><?php echo $post["post_id"]; ?></td>
                          <td><?php echo $post["post_title"]; ?></td>
                          <td><?php echo $post["post_author"]; ?></td>
                          <td><?php echo $post["post_category_id"]; ?></td>
                          <td><?php echo $post["post_date"]; ?></td>
                          <td>
                            <a href="edit_post.php?id=<?php echo $post['post_id']; ?>" class="btn btn-sm btn-primary me-2">Edit</a>
                            <form method="post" action="posts.php" class="d-inline">
                              <input type="hidden" name="delete_post_id" value="<?php echo $post['post_id']; ?>">
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                            </form>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>

                  </table>
            </div>
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->


    <!-- Footer -->
    <?php require_once "includes/admin_footer.php"; ?>