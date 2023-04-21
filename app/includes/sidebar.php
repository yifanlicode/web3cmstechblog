<div class="col-lg-3">
  <div class="card mb-3">
    <div class="card-header">
      <h5>
        <a href="categories.php" class="list-group-item list-group-item-action active">
          Categories
        </a>
      </h5>
    </div>
    <div class="list-group">
      <?php
      $query = "SELECT * FROM categories";
      $statement = $db->prepare($query);
      $statement->execute();
      $categories = $statement->fetchAll();
      $statement->closeCursor();

      foreach ($categories as $category) :
      ?>
        <a href="category_page.php?cat_id=<?= $category['cat_id'] ?>" class="list-group-item list-group-item-action"><?= $category['cat_title'] ?></a>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header">

      <h5>
        <a href="categories.php" class="list-group-item list-group-item-action active">
          Popular Articles
        </a>
      </h5>
    </div>
    <div class="card-body">
      <ul class="list-unstyled">
        <?php
        $query = "SELECT post_id, post_title, post_views_count 
        FROM posts 
        WHERE post_status = 'published' 
        ORDER BY post_views_count DESC 
        LIMIT 8";
        $statement = $db->prepare($query);
        $statement->execute();
        $posts = $statement->fetchAll();
        $statement->closeCursor();

        foreach ($posts as $post) {
          echo "<li><a href='full_page.php?id=" . $post['post_id'] . "'>" . $post['post_title'] . "</a></li>";
        }
        ?>
      </ul>
    </div>
  </div>


</div>
</div>