document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('category').addEventListener('change', function() {
    if (this.value === 'new') {
      document.getElementById('new_category_container').style.display = 'block';
    } else {
      document.getElementById('new_category_container').style.display = 'none';
    }
  });
});