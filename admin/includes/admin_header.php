
<?php
  if (isset($_SESSION['message'])) {
    echo '<div class="container mt-3"><div class="alert alert-success">' . $_SESSION['message'] . '</div></div>';
    unset($_SESSION['message']);
  }
?>
  
  
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web3 Launchpad Admin Panel</title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Admin Panel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="user.php">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="posts.php">Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="categories.php">Categories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="comments.php">Comments</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../app/index.php">View Site</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </header>


    <div class="container my-4" style="min-height: calc(100vh - 80px);">
    <div class="row">