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
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Women in Web3</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Explore</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Learn</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Build</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Jobs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="create.php">New Blog</a>
        </li>
      </ul>
    </div>
  </nav>
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
                    <a href="view.php?id=<?= htmlspecialchars($blog['id']); ?>">Read Full Post</a>
                  <?php else : ?>
                    <p><?= $content ?></p>
                  <?php endif ?>
                </div>
              </div>
            <?php endforeach ?>
        </div>
        <footer>
          <div class="container">
            <p class="text-center">Copyright &copy; Women in Web3 Blog 2023</p>
            <ul class="list-inline text-center">
              <li class="list-inline-item">
                <a href="#">Privacy Policy</a>
              </li>
              <li class="list-inline-item">
                <a href="#">Terms of Use</a>
              </li>
              <li class="list-inline-item">
                <a href="#">Contact Us</a>
              </li>
            </ul>
          </div>
        </footer>

</html>