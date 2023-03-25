<?php

/*******
 *
 * This is the main page for the website
 ****************/

require(__DIR__ . '/connect.php');
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Web3 Launchpad</title>
  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" integrity="sha384-lcNNHT73ZFX53eShLrTfU8q6UEsri6CQj6tgmbY+9XhXx39JKTBBfLNYHl09E0hW" crossorigin="anonymous" />
  <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>
  <!-- Header -->
  <?php include(__DIR__ . '/header.php'); ?>
  <!-- End of Header -->

  <!-- Main Content -->
  <div class="container-fluid">
    <h1 class="text-center mx-auto">Welcome to Web3 Launchpad</h1>
    <p class="text-center mx-auto">Web3 Playground is a vibrant platform where developers can delve into the exciting world of Web3 and blockchain. Whether you're just starting out or a seasoned pro, our community is here to support your journey.</p>
    <hr>
    <?php include(__DIR__ . '/blog-list.php'); ?>
  </div>
  <!-- End of Main Content -->

  <!-- Footer -->
  <?php include 'footer.php'; ?>
  <!-- End of Footer -->

  <!-- JS Scripts -->
  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-i/wG9XJlKZ+lrV7nEJf5cQ8jw5y5lBdYJMSmqWj9XshvZuznjqQaS/y+ImWryM8n" crossorigin="anonymous"></script>
</body>
</html>