
<!-- View a list of pages that already exist in the system with the ability to sort this list. (List should be sorted at a database level not on client.)
- List can be sorted by title, created at date and updated by date. (Or any three columns approved by your instructor.)
- There must be some indication of the type of sorting currently applied to the list.
 -->

<?php

// blog-list.php


require('connect.php');

// Get the sort value from the GET parameter
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// Generate the SQL query based on the sort value
//sort by post_date
if ($sort == 'post_date') {
  $query = "SELECT a.*, c.cat_title as category_name 
            FROM posts a
            INNER JOIN categories c ON a.post_category_id = c.cat_id
            ORDER BY a.post_date ASC
            LIMIT 10
  ";
} else if ($sort == 'update_date') {
  $query = "SELECT a.*, c.cat_title as category_name 
  FROM posts a
  INNER JOIN categories c ON a.post_category_id = c.cat_id
  ORDER BY a.update_date ASC
  LIMIT 10";
} else {
  // Default sort by post_views_count
  $query = "SELECT a.*, c.cat_title as category_name 
            FROM posts a
            INNER JOIN categories c ON a.post_category_id = c.cat_id
            ORDER BY a.post_views_count DESC
            LIMIT 10
  ";
}

$statement = $db->prepare($query);
$statement->execute();
$blogs = $statement->fetchAll(PDO::FETCH_ASSOC);

?>



<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
  <?php foreach ($blogs as $blog) : ?>
    <div class="col">
      <div class="card h-100">
        <div class="card-body">
          <div class="card-text d-flex flex-column align-items-start">
            <h2 class="card-title text-primary">
              <a href="view.php?id=<?= htmlspecialchars($blog['post_id']) ?>">
                <?= htmlspecialchars($blog['post_title']) ?>
              </a>
            </h2>
            <div class="card-text text-muted">
              <small>
              <?= htmlspecialchars($blog['post_author']) ?> -
                <?= date("F d, Y", strtotime($blog['post_date'])) ?>
              </small>
            </div>
<!-- 
            <div class="card-text text-muted">
              <small>
              <?= htmlspecialchars($blog['category_name']) ?> -
              </small>
            </div> -->


            <!-- display blog cover-image -->
            <?php
            $image = htmlspecialchars($blog['post_image']);
            if (strlen($image) > 0) :
            ?>
              <div class="card-text text-muted">
                <img src="<?= $image ?>" alt="cover-image" class="img-fluid">
              </div>
            <?php endif ?>

        
            <!-- display blog content -->
            <?php
            $content = htmlspecialchars($blog['post_content']);
            if (strlen($content) > 200) :
              $truncated_content = substr($content, 0, 200) . "...";            
            ?>
              <div class="card-text text-muted"><?= $truncated_content ?></div>
              <div class="text-end">
              <a href="view.php?id=<?= urlencode($blog['post_id']) ?>">
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
  <!-- End of Blog Post -->
</div>
