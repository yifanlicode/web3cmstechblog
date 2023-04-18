<!-- 
  header file for all pages
-->

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

  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">
  <link rel="stylesheet" href="../public/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" >

  <!-- JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" integrity="sha512-9VxxfO+hNdeNtKDeMVtJN7cbrZIz1w7KdM/0g2sXs3oVRFFmJmwp0Kd1i7B1UEGATLV0ix0rTLPdhjT9XUK1Rg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- Main Quill library -->
  <!-- Include the Quill library --> 
  <script src="//cdn.quilljs.com/1.0.0/quill.js"></script>
  <script src="//cdn.quilljs.com/1.0.0/quill.min.js"></script>

  <!-- Theme included stylesheets -->
  <link href="//cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.core.css"> -->

  <!-- jQuery2023 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- jQuery Validation 2023 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
  <!-- Custom JavaScript -->
  <script src=" ../public/js/validation.js"></script>

</head>


<body>
  <!-- Header -->
  <!-- nav bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Web3 Launchpad</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="true" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse show" id="navbarColor01" style="">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Home
              <span class="visually-hidden">(current)</span>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" href="blog-list.php">Blog List</a>
          </li> -->

          <li class="nav-item">
            <a class="nav-link" href="index.php">Tutorials</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="index.php">Hacthons</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="index.php">Jobs</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="create_page.php">PostBlog</a>
          </li>

        </ul>
        <!-- search bar -->
        <form class="d-flex">
          <input class="form-control me-sm-2" type="search" placeholder="Search">
          <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
        </form>

        <!-- login and logout -->
        <ul class="navbar-nav">
          <?php if (isset($_SESSION['user_id'])) : ?>
            <li class="nav-item">
              <span class="nav-link">Welcome, <?php echo $_SESSION['username']; ?></span>
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


  <?php
  if (isset($_SESSION['message'])) {
    echo '<div class="container mt-3"><div class="alert alert-success">' . $_SESSION['message'] . '</div></div>';
    unset($_SESSION['message']);
  }
  ?>

  <!-- End of Header -->