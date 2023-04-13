<?php
require_once 'connect.php';

if (isset($_POST['email'])) {
  $email = $_POST['email'];
  $stmt = $db->prepare("SELECT * FROM users WHERE user_email = :email LIMIT 1");
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($user) {
    // Email address already exists
    echo 'false';
  } else {
    // Email address is available
    echo 'true';
  }
}
