<?php
// Path: app/includes/blog-list.php
// Add the following code at the beginning of the file after require('includes/connect.php');
if(!isset($_SESSION)) {
  session_start();
}

require('connect.php');

//Switch between sorting options

$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

switch($sort){

  case 'post_date':
    $query = "SELECT a.*, c.cat_title as category_name 
              FROM posts a
              INNER JOIN categories c ON a.post_category_id = c.cat_id
              ORDER BY a.post_date DESC
              LIMIT 18
    ";
    break;

  case 'update_date':
    $query = "SELECT a.*, c.cat_title as category_name 
              FROM posts a
              INNER JOIN categories c ON a.post_category_id = c.cat_id
              ORDER BY a.update_date DESC
              LIMIT 18
    ";
    break;

  case 'post_title':
    $query = "SELECT a.*, c.cat_title as category_name 
              FROM posts a
              INNER JOIN categories c ON a.post_category_id = c.cat_id
              ORDER BY a.post_title ASC
              LIMIT 18
    ";
    break;

  case 'post_views_count':
    $query = "SELECT a.*, c.cat_title as category_name 
              FROM posts a
              INNER JOIN categories c ON a.post_category_id = c.cat_id
              ORDER BY a.post_views_count DESC
              LIMIT 18
    ";
    break;
    
    default:
    $query = "SELECT a.*, c.cat_title as category_name 
              FROM posts a
              INNER JOIN categories c ON a.post_category_id = c.cat_id
              ORDER BY a.update_date DESC
              LIMIT 18
    ";
    break;
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
            <h5 class="card-title text-primary">
              <a href="full_page.php?id=<?= htmlspecialchars($blog['post_id']) ?>">
                <?= htmlspecialchars($blog['post_title']) ?>
              </a>
            </h5>
            <div class="card-text text-muted">
              <small>
              <?= htmlspecialchars($blog['post_author']) ?> -
                <?= date("F d, Y", strtotime($blog['post_date'])) ?>
              </small>
            </div>


            <!-- display blog cover-image -->
            <?php
            $image = htmlspecialchars($blog['post_image']);
            if (strlen($image) > 0) :
            ?>
              <div class="card-text text-muted">
                <img src="uploads/<?= $image ?>" alt="cover-image" class="img-fluid">
              </div>
            <?php endif ?>

    
            <!-- display blog content -->
            <?php
            $content = htmlspecialchars($blog['post_content']);
            if (strlen($content) > 150) :
              $truncated_content = substr($content, 0, 150) . "...";            
            ?>
              <div class="card-text text-muted"><?= $truncated_content ?></div>
              <div class="text-end">
              <a href="full_page.php?id=<?= urlencode($blog['post_id']) ?>">
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
