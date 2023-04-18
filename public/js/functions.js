
$(document).ready(function() {
  $('#category_id').change(function() {
    if ($(this).val() == 'new') {
      $('#new_category_group').show();
    } else {
      $('#new_category_group').hide();
    }
  });
});


//tags input
$(document).ready(function() {
  var tags = [];

  // Add tag when user presses Enter or comma
  $('#tag-input').on('keydown', function(event) {
    if (event.keyCode === 13 || event.keyCode === 188) {
      event.preventDefault();
      addTag();
    }
  });

  // Add tag when user clicks Add Tag button
  $('#add-tag-btn').on('click', function() {
    addTag();
  });

  // Delete tag when user clicks delete button
  $(document).on('click', '.delete-tag-btn', function() {
    var tagIndex = $(this).data('index');
    tags.splice(tagIndex, 1);
    renderTags();
  });

  function addTag() {
    var tag = $('#tag-input').val().trim();
    if (tag) {
      tags.push(tag);
      $('#tag-input').val('');
      renderTags();
      $('#hidden-tag-input').val(tags.join(','));
    }
  }
  

  function renderTags() {
    var tagListHtml = '';
    for (var i = 0; i < tags.length; i++) {
      tagListHtml += '<span class="badge bg-secondary">' + tags[i] +
        '<button type="button" class="close delete-tag-btn" data-index="' + i + '">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button></span>';
    }
    $('#tag-list').html(tagListHtml);
    $('#hidden-tag-input').val(tags.join(','));
  }
  
});


//add image name to input field USE JQUERY
$(document).ready(function() {
  $('#page_image').on('change', function() {
    let fileName = $(this).val().split('\\').pop();
    $('#page_image_label').val(fileName);
  });
});








     
