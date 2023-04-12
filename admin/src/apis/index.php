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
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css"> -->
  <link rel="icon" href="path/to/favicon.ico" type="image/x-icon"> 
  <link rel="stylesheet" href="../../public/css/bootstrap.min.css">
  <link rel="stylesheet/scss" href="../../public/css/_bootswatch.scss">
  <link rel="stylesheet" href="../../public/css/style.css">
  <!-- SEO  -->
  <meta name="description" content="Web3 Launchpad is a one-stop destination for anyone looking to learn and work in the Web3 and blockchain industry.">
<meta name="keywords" content="Web3, Blockchain, Dev, Learning, Tutorials">

</head>

<body>
  <!-- Header -->
  <?php include(__DIR__ . '/includes/header.php'); ?>
  <!-- End of Header -->

 <!-- Main Content -->
<div class="container-fluid">
  <h1 class="text-center mx-auto">Welcome to Web3 Launchpad</h1>
  <p class="text-center mx-auto">Web3 Playground is a vibrant platform where developers can delve into the exciting world of Web3 and blockchain. Whether you're just starting out or a seasoned pro, our community is here to support your journey.</p>
  <hr>

  <div class="row">
    <div class="col-lg-9">
      <!-- Include blog-list.php -->
      <?php include(__DIR__ . '/blog-list.php'); ?>
    </div>

    <div class="col-lg-3">
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Popular articles -->
        <h3>Popular Articles</h3>
        <!-- Include popular-articles.php -->

        <!-- Tags cloud -->
        <h3>Tags Cloud</h3>
        <!-- Include tags-cloud.php -->
      </div>
    </div>
  </div>
</div>
<!-- End of Main Content -->


  <!-- Footer -->
  <?php include 'includes/footer.php'; ?>
  <!-- End of Footer -->

  <!-- JS Scripts -->
  <!-- JavaScript Bundle with Popper -->
  <script rel="stylesheet" href="../../public/css/bootstrap.min.css" integrity="sha384-Ed2grpAAfUgVfDfBVHPj9+15Ek329o8swXCSgTxHkzubDidTNJ3lk2bXJHr3Tfz9" crossorigin="anonymous"></script>
</body>
</html>