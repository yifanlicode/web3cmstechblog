<?php

/*******
 *
 * This is the category page for the website
 * category page
 * get the category id from the url and display the posts in that category
 * 
 ****************/

if (!isset($_SESSION)) {
  session_start();
}

require 'includes/connect.php';

//Get the category id from the url
$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 10;
$offset = ($page - 1) * $per_page;

// Query the posts table to get all the posts in that category 
// (only show posts with post_status = published)

$query = "SELECT p.*,c.cat_title as cat_title
          FROM posts p
          INNER JOIN categories c ON p.post_category_id = c.cat_id
          WHERE p.post_status = 'published' AND p.post_category_id = :cat_id
          ORDER BY p.post_date DESC
          LIMIT :per_page OFFSET :offset";

$statement = $db -> prepare($query);
$statement -> bindValue(':cat_id', $cat_id, PDO::PARAM_INT);
$statement -> bindValue(':per_page', $per_page, PDO::PARAM_INT);
$statement -> bindValue(':offset', $offset, PDO::PARAM_INT);
$statement -> execute();
$posts = $statement -> fetchAll(PDO::FETCH_ASSOC);

//Calculate total number of articles and total number of pages

$query = " SELECT COUNT(*) as total
           FROM posts
           WHERE post_status = 'published' AND post_category_id = :cat_id";

$statement = $db -> prepare($query);
$statement -> bindValue(':cat_id', $cat_id, PDO::PARAM_INT);
$statement -> execute();
$total = $statement->fetchColumn();
$total_pages = ceil($total / $per_page);

include('includes/header.php');

?>


<div class="container my-5">
  <div class="row">
    <div class="col-lg-9">
      
    <!-- Main Content -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
      <?php foreach ($posts as $post) : ?>
        <div class="col">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="card-text d-flex flex-column align-items-start">
                      <h2 class="card-title text-primary">
                        <a href="full_page.php?id=<?= htmlspecialchars($post['post_id']) ?>">
                          <?= htmlspecialchars($post['post_title']) ?>
                          </a>
                     </h2>
                      <div class="card-text text-muted">
                        <small>
                          <?= htmlspecialchars($post['post_author']) ?> -
                          <?= date("F d, Y", strtotime($post['post_date'])) ?>
                      </small>
                       </div>
                        <!-- display blog cover-image -->
                        <?php
                                $image = htmlspecialchars($post['post_image']);
                                if (strlen($image) > 0) :
                        ?>
                        <div class="card-text text-muted">
                        <img src="uploads/<?= $image ?>" alt="cover-image" class="img-fluid cover-image">
                        </div>
                        <?php endif ?>

                        <!-- display blog content -->
                        <?php
                            $content = htmlspecialchars($post['post_content']);
                            if (strlen($content) > 200) :
                              $truncated_content = substr($content, 0, 200) . "...";            
                        ?>
                        <div class="card-text text-muted"><?= $truncated_content ?></div>
                        <div class="text-end">
                        <a href="full_page.php?id=<?= urlencode($post['post_id']) ?>">
                            Read more >>
                          </a>
                        </div>
                       <?php else : ?>
                      <div class="card-text"><?= $content ?></div>
                          <?php endif ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach ?>
    </div>


<!-- Pagination -->
<nav aria-label="Page navigation" class="mt-4">
  <ul class="pagination justify-content-center">
  <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
     <a class="page-link" href="?cat_id=<?= $cat_id ?>&page=<?= $page - 1 ?>">Previous</a>
  </li>
  <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
    <a class="page-link" href="?cat_id=<?= $cat_id ?>&page=<?= $i ?>"><?= $i ?></a>
    </li>
  <?php endfor ?>
  <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
    <a class="page-link" href="?cat_id=<?= $cat_id ?>&page=<?= $page + 1 ?>">Next</a>
  </li>
  </ul>
</nav>
<!-- End of Pagination -->
  </div>
<!-- End of Main Content -->

    <!-- Sidebar -->

    <?php include 'includes/sidebar.php'; ?>

    <!-- End of sidebar -->

  </div>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>
<!-- End of Footer -->