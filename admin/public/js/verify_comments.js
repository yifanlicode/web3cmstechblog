function validateComment() {
    const commentContent = document.querySelector("textarea[name='comment_content']").value.trim();

    if (commentContent.length < 8) {
      alert("Your comment must be at least 8 characters long.");
      return false;
    }

    return true;
  }