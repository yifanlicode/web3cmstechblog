<?php

// blog-list.php
// Get the 10 most recent posts
$query = "SELECT a.*, u.username as author, c.name as category_name 
          FROM Articles a
          INNER JOIN Categories c ON a.category_id = c.id
          INNER JOIN Users u ON a.user_id = u.id
          ORDER BY a.created_at DESC
          LIMIT 10
";

$statement = $db->prepare($query);
$statement->execute();
$blogs = $statement->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
  <!-- Blog Post -->
  <?php foreach ($blogs as $blog) : ?>
    <div class="col">
      <div class="card h-100">
        <div class="card-body">
          <div class="card-text d-flex flex-column align-items-start">
            <h2 class="card-title text-primary">
              <a href="view.php?id=<?= htmlspecialchars($blog['id']) ?>">
                <?= htmlspecialchars($blog['title']) ?>
              </a>
            </h2>
            <div class="card-text text-muted">
              <small>
                <?= htmlspecialchars($blog['category_name']) ?> -
                <?= date("F d, Y", strtotime($blog['created_at'])) ?>
              </small>
            </div>
            <!-- display blog cover-image -->
            <?php if ($blog['cover_image']) : ?>
              <img src="<?= htmlspecialchars($blog['cover_image']) ?>" class="img-fluid" alt="cover-image">
            <?php else : ?>
              <img src="path/to/default-image.jpg" class="img-fluid" alt="default-cover-image">
            <?php endif ?>

            <!-- display blog content -->
            <?php
            $content = htmlspecialchars($blog['content']);
            if (strlen($content) > 200) :
              $truncated_content = substr($content, 0, 200) . "...";            
            ?>
              <div class="card-text text-muted"><?= $truncated_content ?></div>
              <div class="text-end">
              <a href="view.php?id=<?= urlencode($blog['id']) ?>">
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