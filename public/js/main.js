// function sortPosts(value) {
//   let currentUrl = new URL(window.location.href);
//   currentUrl.searchParams.set('sort', value);
//   window.location.href = currentUrl.toString();
// }


function sortPosts(sortType) {
  const encodedSortType = encodeURIComponent(sortType);
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      const blogListContainer = document.getElementById('blog-list-container');
      blogListContainer.innerHTML = this.responseText;
    }
  };
  xhr.open('GET', `includes/blog-list.php?sort=${encodedSortType}`, true);
  xhr.send();
}