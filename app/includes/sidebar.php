<div class="col-lg-3">
  <div class="card mb-3">
    <div class="card-header">
      <h5>Categories</h5>
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
          <a href="categories.php?cat_id=<?= $category['cat_id'] ?>" class="list-group-item list-group-item-action"><?= $category['cat_title'] ?></a>
        <?php endforeach; ?>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header">
      <h5>Popular Articles</h5>
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

  <div class="card mb-3">
    <div class="card-header">
      <h5>Tags Cloud</h5>
    </div>
    <div class="card-body">
      <?php
      $query = "SELECT DISTINCT post_tags FROM posts WHERE post_status = 'published' ORDER BY post_tags ASC";
      $statement = $db->prepare($query);
      $statement->execute();
      $tags = $statement->fetchAll();
      $statement->closeCursor();
      foreach ($tags as $tag) {
        $tagName = $tag['post_tags'];
        $tagLink = "blog-list.php?tag=$tagName";
      ?>
        <a href="<?= $tagLink ?>" class="badge badge-primary"><?= $tagName ?></a>
      <?php } ?>
    </div>
  </div>
</div>
