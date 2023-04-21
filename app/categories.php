<?php

// show all the categories in the database in a card with a link to the category page

require 'includes/connect.php';


// Query the categories table to get all the categories
$query = "SELECT * FROM categories";
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll();

include 'includes/header.php';


?>


<div class="container my-5">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Browse Categories</h5>
      <p class="card-text">Discover the world of Web3 – easily navigate our learning platform by category and unleash your creativity</p>
    </div>
  </div>
</div>

<div class="container my-5">
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php foreach ($categories as $category) : ?>
      <div class="col">
        <div class="card h-100">
          <!-- <img src="path/to/your/category/image" class="card-img-top" alt="Category Image"> -->
          <div class="card-body">
            <h5 class="card-title"><?= $category['cat_title'] ?></h5>
            <p class="card-text">Dive deeper into your interests</p>
          </div>
          <div class="card-footer">
            <a href="category_page.php?cat_id=<?= $category['cat_id'] ?>" class="btn btn-primary">View Articles</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
