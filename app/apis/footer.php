<!-- Footer -->
<footer class="bg-light py-3">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <p class="mb-0 text-center text-lg-start">© 2023 Web3 Launchpad</p>
      </div>
      <div class="col-lg-4">
        <h5 class="mb-3 text-center">Subscribe to Our Newsletter</h5>
        <form class="subscribe-form d-flex flex-row">
          <div class="form-group">
            <input type="email" class="form-control me-2" placeholder="Enter your email address">
          </div>
          <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>
      </div>
      <div class="col-lg-4">
        <h5 class="mb-3 text-center">Follow Us</h5>
        <ul class="list-unstyled d-flex justify-content-center">
          <li class="me-4">
            <a href="https://twitter.com/" target="_blank">
            Twitter <i class="fab fa-twitter fa-2x"></i>
            </a>
          </li>
          <li class="me-4">
            <a href="https://github.com/" target="_blank">
             Github <i class="fab fa-github fa-2x"></i>
            </a>
          </li>
          <li class="me-4">
            <a href="https://discord.com/" target="_blank">
            Discord  <i class="fab fa-discord fa-2x"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!-- JS Scripts -->
  
<!-- Sort posts -->
<script>
  function sortPosts(sortType) {
    const encodedSortType = encodeURIComponent(sortType);
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
        const blogListContainer = document.getElementById('blog-list-container');
        blogListContainer.innerHTML = this.responseText;
      }
    };
    xhr.open('GET', `blog-list.php?sort=${encodedSortType}`, true);
    xhr.send();
  }
</script>

</body>
</html>

<!-- End of Footer -->
