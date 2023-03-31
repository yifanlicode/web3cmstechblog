<div>
  <h5>Tags Cloud</h5>
</div>
<div class="tags-cloud">
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
