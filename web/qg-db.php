<?php

$host = '127.0.0.1';
$port = 3309;
$database = 'drupal';
$username = 'pantheon';
$password = 'pantheon';

try {
  $conn = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "SELECT * FROM users LIMIT 1";
  $stmt = $conn->query($sql);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
      print_r($row); // Output the row data
  } else {
      echo "No rows found.";
  }
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

?>


