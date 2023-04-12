<!-- show the user comments 

View and moderate comments on pages submitted by non-admins.
- Feature 2.9 must be implemented. In other words there must be comments for you to moderate.
- Comment moderation can take the form of deleting comments, hiding them from public view, or "disemvoweling" comments.
- Only admin users can moderate comments.

Comment on specific pages.
- Each page should contain a form where a user can submit a comment.
- If you are allowing non-logged in users to comment then you need to allow them to submit a name with their comment.
- Once a comment is submitted it should be displayed along with the page.
- Comments should be displayed in reverse chronological order.
- Comment forms must *not* be WYSIWYG.

- If the CAPTCHA is not submitted correctly the users comment should not be accepted.
- If the CAPTCHA is not submitted correctly the users should be given another chance without having to retype their comment.

-->

<?php
// Path: src/apis/comments.php
// show the user comments
function show_comments($post_id) {
  global $db;
  $query = "SELECT * FROM comments WHERE comment_post_id = :post_id AND comment_status = 'approved' ORDER BY comment_id DESC";
  $statement = $db->prepare($query);
  $statement->bindValue(':post_id', $post_id);
  $statement->execute();
  $comments = $statement->fetchAll();
  $statement->closeCursor();
  return $comments;
}

// Comment on specific pages.

// Each page should contain a form where a user can submit a comment.
// If you are allowing non-logged in users to comment then you need to allow them to submit a name with their comment.
// Once a comment is submitted it should be displayed along with the page.
// Comments should be displayed in reverse chronological order.
