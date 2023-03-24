<?php

/*******
 *
 * This is the main page for the blog
 * It will display the most recent 10 posts
 ****************/

require('connect.php');

// Get the 10 most recent posts
$query = "SELECT a.*, u.username as author, c.name as category, t.name as tag
    FROM Articles a
    LEFT JOIN Users u ON a.user_id = u.id
    LEFT JOIN Categories c ON a.category_id = c.id
    LEFT JOIN ArticleTags at ON a.id = at.article_id
    LEFT JOIN Tags t ON at.tag_id = t.id
    ORDER BY a.created_at DESC
    LIMIT 10";

$statement = $db->prepare($query);
$statement->execute();
$blogs = $statement->fetchAll();

?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Women in Web3 Blog</title>
  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>
  <!-- Header -->
  <?php include 'header.php'; ?>
  <!-- End of Header -->


  <!-- Main Content -->
  <div class="container">
    <h1 class="text-center">Welcome to Women in Web3</h1>
    <p class="text-center">Stay updated with the latest news and trends in the world of Web3 and blockchain technology.</p>
    <div class="row">

      <!-- Blog Post -->
        <div id="all_blogs">
            <?php foreach ($blogs as $blog) : ?>
              <div class="blog_post">
                <h2>
                  <a href="view.php?id=<?= htmlspecialchars($blog['id']) ?>">
                    <?= htmlspecialchars($blog['title']) ?></a>
                </h2>
                <p>
                  <small>
                    Category: <?= htmlspecialchars($blog['category']) ?> - Author: <?= htmlspecialchars($blog['author']) ?> - Tags:
                    <?= htmlspecialchars($blog['tag']) ?>
                    - Date: <?= date("F d,Y,h:i a", strtotime($blog['created_at'])) ?>
                  </small>
                </p>
                <div class="blog_content">
                  <?php
                  $content = htmlspecialchars($blog['content']);
                  if (strlen($content) > 200) :
                    $truncated_content = substr($content, 0, 200) . "...";
                  ?>
                    <p><?= $truncated_content ?></p>
                    <a href="view.php?id=<?= htmlspecialchars($blog['id']); ?>">Read Full Blog</a>
                  <?php else : ?>
                    <p><?= $content ?></p>
                  <?php endif ?>
                </div>
              </div>
            <?php endforeach ?>
        </div>
      <!-- End of Blog Post -->

      <!-- Footer -->
      <?php include 'footer.php'; ?>
      <!-- End of Footer -->
</html>