<!-- Popular Articles -->
<div>
  <h5>Popular Articles</h5>
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
        echo "<li><a href='single.php?id=" . $post['post_id'] . "'>" . $post['post_title'] . "</a></li>";
      }
    ?>
  </ul>
</div>
