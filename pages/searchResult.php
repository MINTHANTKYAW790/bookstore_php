<?php
session_start();
require("../includes/head.php");

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
?>

  <body>
    <nav class="row row-cols-1 row-cols-xs-1 row-cols-sm-1 row-cols-md-3 row-cols-lg-3 py-3 ">
      <a href="../pages/index.php" class="row row-cols-2">
        <img src="../img/wordwise.png" alt="This is the bookstore logo" class="logoImage">
        <h3 class="text-orange p-0 m-0 wordwise">WORD WISE</h3>
      </a>

      <form class="d-flex" action="../pages/searchResult.php" method="GET">
        <input type="text" name="query" class="form-control search" value="<?php echo $search_query ?>">
        <input type="submit" value="Search" class=" btn btn-orange text-white search">
      </form>

      <?php

      if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

        echo "
            <div class='navButton'>
            <a href='../pages/signIn.php'><button type='button' class='btn btn-primary'>Admin Sign in</button></a>
            
            
          </div>
            
            ";
      } else {
        echo "<div class='navButton'>
                <a href='../pages/signOut.php'><button type='button' class='btn btn-danger'>Sign Out</button></a>
              </div>";
      }
      ?>
    </nav>

    <?php
    include("../includes/pagination.php");
    ?>

    <div class="gridContainer row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5">
    <?php

    // SQL query to search across multiple tables (books, authors, categories, publishing houses)
    $sql = "SELECT * FROM bookss
              RIGHT JOIN authors ON bookss.author_id = authors.id
              RIGHT JOIN category ON bookss.category_id = category.id
              RIGHT JOIN publishing_house ON bookss.publishing_house_id = publishing_house.id
              WHERE bookss.name LIKE '%$search_query%'
              OR authors.author_name LIKE '%$search_query%'
              OR category.category_name LIKE '%$search_query%'
              OR publishing_house.publishing_house_name LIKE '%$search_query%'";

    echo '<h4 class="booksText">Search Result For : ' . $search_query . '</h4>';

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Output data of each row
      while ($row = $result->fetch_assoc()) {
        $books = "SELECT * FROM bookss WHERE $row[id] = bookss.category_id";
        $booksResult = $conn->query($books);
        $booksRow = $booksResult->fetch_assoc();

        echo "<div class='imageBox '>
        <a href='../pages/detail.php?id=$row[id]'>

          <img class='imageImageBox' src='../img/" . $row['image'] . "'  alt='image' height='309px' width='224px''/>
          
          <div class='title'><i class='fa-solid fa-magnifying-glass'></i></div>
          <p style='color:black;' class='authorIndex'>$row[author_name]</p>
          <p style='color:black;' class='statusIndex'>$row[name]</p>
          
          <p style='color:black;' class='authorIndex'>$row[category_name]</p>
          <p style='color:black;' class='authorIndex'>$row[publishing_house_name]</p>
            
        </a>
      </div>";
      }
    } else {
      echo "<div>No results found</div>";
    }

    // Close database connection
    $conn->close();
  }


    ?>
    </div>

    <?php
    include("../includes/footer.php");
    ?>

  </body>

  </html>