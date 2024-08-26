

<?php
require '../includes/session.php';
?>

<?php
// Database connection parameters
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['id'];

  // Delete record from database
  $query = "DELETE FROM bookss WHERE id = :id";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':id', $id);

  if ($stmt->execute()) {
    header("Location: admin.php");
    exit;
  } else {
    echo "Error deleting record.";
  }
}
?>
