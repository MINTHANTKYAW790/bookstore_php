<!-- // session.php -->
<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: signIn.php");
  exit();
}



// echo "Hello, " . htmlspecialchars($_SESSION['sessionUsername']) . ". This is another page.";
// echo "Hello, " . htmlspecialchars($_SESSION['session_token']) . ". This is another page.";
?>