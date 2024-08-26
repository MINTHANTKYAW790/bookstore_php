<?php


// Establish database connection (replace with your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wordwise";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$search_query = "";
// Process search query
if (isset($_GET['query'])) {
  $search_query = $_GET['query'];
}

?>

<nav class="row row-cols-1 row-cols-xs-1 row-cols-sm-1 row-cols-md-3 row-cols-lg-3 py-3 ">
  <a href="../pages/index.php" class="row row-cols-2" style="width: 25%;">
    <img src="../img/wordwise.png" alt="This is the bookstore logo" class="logoImage">
    <h3 class="text-orange p-0 m-0 wordwise">WORD WISE</h3>
  </a>

  <form class="d-flex" action="../pages/searchResult.php" method="GET" style="width: 50%;">
    <input type="text" name="query" class="form-control search" placeholder="Search everything you want here">
    <input type="submit" value="Search" class=" btn btn-orange text-white search">
  </form>

  <?php

  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

    echo "
    <div class='navButton' style='width: 25%;'>
    <a href='../pages/signIn.php'><button type='button' class='btn btn-orange'>Admin Sign in</button></a>
    
    
  </div>
    ";
  } else {
    echo "<div class='navButton' style='width: 25%;'>
    <a href='../pages/signOut.php' class='btn btn-danger'>Sign Out</a>
  </div>";
  }
  ?>
</nav>