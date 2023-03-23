<?php


//Define database credentials

const DB_HOST = 'mysql:host=localhost;dbname=web3techblog_db;charset=utf8';
const DB_USER = 'yifan';
const DB_PASS = 'admin';


//  PDO is PHP Data Objects

try {
  //try creating a new PDO instance to MySQL 
  $db = new PDO(DB_HOST, DB_USER, DB_PASS);
  //set PDO error mode to exception
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo 'Connected to database';
} catch (PDOException $e) {
  //if there is an error, display the error message
  echo "Connection failed: " . $e->getMessage();
  exit();
}


?>