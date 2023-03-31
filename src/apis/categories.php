

<div>
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
    <a href="category.php?cat_id=<?= $category['cat_id'] ?>" class="list-group-item list-group-item-action"><?= $category['cat_title'] ?></a>
  <?php endforeach; ?>
</div>
