<?php


if (!isset($_SESSION)) { // if session is not started then start it 
  session_start();
}

require 'includes/connect.php';


// Get the search query and category from the request
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$page = isset($_GET['page']) ? trim($_GET['page']) : 1;
$per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 10;
$offset = ($page - 1) * $per_page;

//Prepare the query based on the search query and category
// $query = "SELECT p.*,c.cat_title as cat_title 
//           FROM posts p
//           INNER JOIN categories c ON p.post_category_id = c.cat_id
//           WHERE p.post_status = 'published' 
//           AND (p.post_title LIKE :search_query OR p.post_content LIKE :search_query)";

// only search in the title
$query = "SELECT p.*,c.cat_title as cat_title 
          FROM posts p
          INNER JOIN categories c ON p.post_category_id = c.cat_id
          WHERE p.post_status = 'published' 
          AND p.post_title LIKE :search_query";

if ($category != 0) {
  $query .= " AND p.post_category_id = :category";
}

// Add the order by and limit clauses to the query to paginate the results
$query .= " ORDER BY p.post_date DESC
            LIMIT :per_page OFFSET :offset"; // limit the number of results


// Execute the query
$statement = $db->prepare($query);
$statement->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);

if ($category != 0) {
  $statement->bindValue(':category', $category, PDO::PARAM_INT);
}


$statement->bindValue(':per_page', $per_page, PDO::PARAM_INT);
$statement->bindValue(':offset', $offset, PDO::PARAM_INT);

// Fetch the results
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);


// Calculate total number of articles and total number of pages
$query = "SELECT COUNT(*) as total 
          FROM posts p
          WHERE p.post_status = 'published' 
          AND (p.post_title LIKE :search_query OR p.post_content LIKE :search_query)";

if ($category != 0) {
  $query .= " AND p.post_category_id = :category";
}

$statement = $db->prepare($query);
$statement->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);

if ($category != 0) {
  $statement->bindValue(':category', $category, PDO::PARAM_INT);
}

$statement->execute();
$total = $statement->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total / $per_page);
$show_pagination = $total > 10;

include 'includes/header.php';

?>


<!-- Display search results here -->
<div class="container my-5">
  <div class="row">
    <div class="col-lg-9">
      <!-- Main Content -->
      <h3 class="mb-4">Search results for "<?= htmlspecialchars($search_query) ?>"</h3>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
        <?php if (count($posts) > 0) : ?>
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
        <?php else : ?>
          <div class="col-12">
            <p>No search results found.</p>
          </div>
        <?php endif ?>
      </div>


      <!-- Pagination -->
      <?php if ($show_pagination) : ?>
        <nav aria-label="Page navigation" class="mt-4">
          <ul class="pagination justify-content-center">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="?search_query=<?= urlencode($search_query) ?>&category=<?= $category ?>&page=<?= $page - 1 ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
              <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?search_query=<?= urlencode($search_query) ?>&category=<?= $category ?>&page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor ?>
            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
              <a class="page-link" href="?search_query=<?= urlencode($search_query) ?>&category=<?= $category ?>&page=<?= $page + 1 ?>">Next</a>
            </li>
          </ul>
        </nav>
      <?php endif ?>
    </div>

    <!-- Sidebar Widgets Column -->
    <?php include 'includes/sidebar.php'; ?>
  </div>




  <?php include 'includes/footer.php'; ?>