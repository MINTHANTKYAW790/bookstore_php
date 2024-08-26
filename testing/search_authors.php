<?php
// Database connection
$host = 'localhost';
$db = 'wordwise';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db";
$options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

// Check if a search term is provided
if (isset($_POST['term'])) {
  $term = $_POST['term'] . '%';
  $stmt = $pdo->prepare("SELECT author_name FROM authors WHERE author_name LIKE :term");
  $stmt->bindValue(':term', $term, PDO::PARAM_STR);
  $stmt->execute();
  $authors = $stmt->fetchAll(PDO::FETCH_COLUMN);

  // Return the author names as a JSON array
  echo json_encode($authors);
}
