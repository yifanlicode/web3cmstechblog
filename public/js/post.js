

select.addEventListener('change', function() {
  if (select.value === 'new') {
    newCategoryDiv.style.display = 'block';
    newCategoryInput.focus();
  } else {
    newCategoryDiv.style.display = 'none';
  }
});


addCategoryBtn.addEventListener('click', function() {
  const categoryTitle = newCategoryInput.value;
  if (categoryTitle.trim() === '') {
    alert('Please enter a category name.');
    return;
  }

  // Make an AJAX request to add the new category to the database
  // and reload the page to display the new category in the dropdown list
  // Alternatively, you can submit the form using JavaScript to add the new category
  // and display a success message to the user without reloading the page
  // Example AJAX request using jQuery:
  // $.post('add_category.php', { title: categoryTitle })
  //   .done(function(response) {
  //     location.reload();
  //   })
  //   .fail(function(jqXHR, textStatus, errorThrown) {
  //     alert('Failed to add the new category.');
  //   });

  // Set the value of the new category option to the ID of the newly added category
  const newOption = document.createElement('option');
  newOption.value = 'new_' + categoryTitle;
  newOption.textContent = categoryTitle;
  select.insertBefore(newOption, select.lastChild);
  select.value = newOption.value;

  newCategoryDiv.style.display = 'none';
});
