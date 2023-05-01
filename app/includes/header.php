<!-- 
  header file for all pages
-->
<?php
  if (isset($_SESSION['message'])) {
    echo '<div class="container mt-3"><div class="alert alert-success">' . $_SESSION['message'] . '</div></div>';
    unset($_SESSION['message']);
  }


// Fetch all categories from the database
require_once 'includes/connect.php';
$category_query = "SELECT * FROM categories";
$category_result = $db->query($category_query);
$categories = $category_result->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <!-- SEO  -->
  <meta name="description" content="Web3 Launchpad is a one-stop destination for anyone looking to learn and work in the Web3 and blockchain industry.">
  <meta name="keywords" content="Web3, Blockchain, Dev, Learning, Tutorials">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Web3 Launchpad</title>

  <!-- JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" integrity="sha512-9VxxfO+hNdeNtKDeMVtJN7cbrZIz1w7KdM/0g2sXs3oVRFFmJmwp0Kd1i7B1UEGATLV0ix0rTLPdhjT9XUK1Rg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- Include the Tinymce library --> 
  <script src="https://cdn.tiny.cloud/1/v2iy4oyzovirp5rwlcwnr2tejnfss5ffuu2x7zrv29jyowgy/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  
  <!--   Custom CSS -->
  <link rel="stylesheet" href="../public/css/style.css">

  <!-- jQuery2023 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- jQuery Validation 2023 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
  <!-- Custom JavaScript -->
  <script src=" ../public/js/validation.js"></script>
  <script src=" ../public/js/functions.js"></script>


  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">
  <link rel="stylesheet" href="../public/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" >


</head>


<body>
  <!-- Header -->
  <!-- nav bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Web3 Launchpad</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="true" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse show" id="navbarColor01">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Home
              <span class="visually-hidden">(current)</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="category_page.php?cat_id=15">Guides</a>
          </li>

          <li class="nav-item">
          <a class="nav-link" href="category_page.php?cat_id=5">Hacthons</a>
          </li>

          <li class="nav-item">
          <a class="nav-link" href="category_page.php?cat_id=15">Tech</a>
          </li>

          <li class="nav-item">
          <a class="nav-link d-flex align-items-center" href="create_page.php">
            <i class="fas fa-pencil-alt me-2"></i>Write
          </a>
        </li>
        
        </ul>

        <!-- search bar -->
        <form action="search.php" method="GET" class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_query">
      
        <select class="form-select me-2" aria-label="Category" name="category">
        <option value="0">All Categories</option>
        <?php foreach ($categories as $category) : ?>
          <option value="<?= $category['cat_id'] ?>"><?= htmlspecialchars($category['cat_title']) ?></option>
        <?php endforeach; ?>
         </select>

        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
        </form>

        <!-- login and logout -->
        <ul class="navbar-nav">
        
          <?php if (isset($_SESSION['user_id'])) : ?>
            <li class="nav-item">
              <span class="nav-link">Hi, <?php echo $_SESSION['username']; ?></span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>


  

  <!-- End of Header -->