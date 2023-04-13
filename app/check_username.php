<?php
require_once 'connect.php';

if (isset($_POST['username'])) {
  $username = $_POST['username'];
  $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($user) {
    // Username already exists
    echo 'false';
  } else {
    // Username is available
    echo 'true';
  }
}
