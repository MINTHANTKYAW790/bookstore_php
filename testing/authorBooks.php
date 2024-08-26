<?php
require '../includes/session.php';

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Word Wise</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/ff5868ab46.js" crossorigin="anonymous"></script>
  <link rel="icon" type="image/x-icon" href="../img/wordwise.png">
  <link rel="stylesheet" href="../CSS/style.css">
  <link rel="stylesheet" href="../CSS/astyle.css">
</head>

<body>

  <?php
  include("../includes/navbar.php");
  include("../includes/pagination.php");
  ?>

  <div class="gridContainer row row-cols-1 row-cols-sm-2 row-cols-md-5 row-cols-lg-5">
    <!-- PHP code started from here!!! -->
    <?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "wordwise";

    $connection = new mysqli($servername, $username, $password, $database);

    if ($connection->connect_error) {
      die("Connection failed!" . $connection->connect_error);
    }

    $sql = "SELECT * FROM bookss";
    $result = $connection->query($sql);

    if (!$result) {
      die("Invalid Query!" . $connection->error);
    }

    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      // GET method: Show the data of the client
      if (!isset($_GET["id"])) {
        header("location: /BookStore/pages/newAuthor.php");
        exit;
      }
      $id = $_GET["id"];


      $tail = "SELECT * FROM authors WHERE authors.id=$id";
      $tailResult = $connection->query($tail);



      while ($tailRow = $tailResult->fetch_assoc()) {
        if ($tailRow["author_image"] == "") {
          $tailRow["author_image"] = "user.jpg";
        }
        echo "
      <div class='detailContainer mySlides'>
      <div class='insideDetailTextContainer row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-2'>
        <div class='imageContainer' style='width: 30%;'>
          <img src='../authorImage/" . $tailRow['author_image'] . "' alt='image' width=' 352px' height='485px'>
        </div>
        <div style='width: 60%;' class='detailTextContainer'>
          <h5 class='detailText author'>$tailRow[author_name]</h5>
  
          <p class='detailText desciption'>$tailRow[description]</p>
  
        </div>
      </div>
    </div>

      <h4 class='booksText'>BOOKS LIST / $tailRow[author_name]</h4>";
      }




      // <!-- PAGINATION BUTTONS PROCESS -->
      $dsn = "mysql:host=$servername;dbname=$database";

      $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      );

      try {
        $pdo = new PDO($dsn, $username, $password, $options);
      } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
      }

      // Define the number of images per page
      $booksPerPage = 10;

      // Get the current page number from the query string (default to 1 if not set)
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

      // Calculate the offset for the SQL query
      $offset = ($page - 1) * $booksPerPage;

      // Fetch the total number of images
      $stmt = $pdo->query("SELECT COUNT(*) FROM bookss WHERE bookss.author_id = $id");
      $totalBooks = $stmt->fetchColumn();

      // Calculate the total number of pages
      $totalPages = ceil($totalBooks / $booksPerPage);

      // Fetch the images for the current page
      $stmt = $pdo->prepare("SELECT * FROM bookss WHERE bookss.author_id = $id LIMIT :limit OFFSET :offset ");
      $stmt->bindValue(':limit', $booksPerPage, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->execute();
      $books = $stmt->fetchAll();


      foreach ($books as $book) :

        echo " <div class='imageBox '>
                        <a href='../pages/detail.php?id=$book[id]'>
  
                          <img class='imageImageBox' src='../img/" . $book['image'] . "'  alt='image' height='309px' width='224px''/>
                          <div class='title'><i class='fa-solid fa-magnifying-glass'></i></div>
                        
                          <p style='color:black' class='statusIndex'>$book[name]</p>
  
                        </a>
                      </div>";

      endforeach;
    }
    ?>

    <!-- This is in the display period of the BOOKS page -->



    <!-- //read the row of the selected client from database table

    if ($result->num_rows > 0) {
    // Output data of each row -->




    <!-- } else {
            echo "
            <h6 style='color:black;'>Comming Soon!</h6>
          
            ";
          } -->





  </div>
  </div>



  <!-- PAGINATION DIV -->
  <div class=" paginationNum row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3">
    <div>
      <?php if ($page > 1) : ?>
        <a href="?page=<?php echo $page - 1; ?>" style="color:black" class="btn btn-orange">&laquo; Previous</a>
      <?php endif; ?>
    </div>

    <div style="text-align: center;"><?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?> btn btn-orange" style="color:black" class="">
          <?php echo $i; ?>
        </a>
      <?php endfor; ?>
    </div>

    <div style="text-align: right;">
      <?php if ($page < $totalPages) : ?>
        <a href="?page=<?php echo $page + 1; ?>" style="color:black" class="btn btn-orange">Next &raquo;</a>
      <?php endif; ?>
    </div>
  </div>
  <!-- END OF PAGINATION DIV -->




  <!-- try {
  //read the row of the selected client from database table
  $sql = "SELECT DISTINCT *
  FROM bookss
  WHERE bookss.author_id = $id";

  $result = $connection->query($sql);
  if ($result->num_rows > 0) {
  // Output data of each row
  while ($row = $result->fetch_assoc()) {

  echo " <div class='imageBox '>
    <a href='../pages/detail.php?id=$row[id]'>

      <img class='imageImageBox' src='../img/" . $row[' image'] . "'  alt='image' height='309px' width='224px''/>
                    <div class='title'><i class='fa-solid fa-magnifying-glass'></i></div>
                   
                    <p style='color:black' class='statusIndex'>$row[name]</p>
  
                  </a>
                </div>" ; } $result=-1; } else { echo "
        <h6 style='color:black;'>Comming Soon!</h6> -->

  <!-- " ; } } catch (mysqli_sql_exception $e) { var_dump($e); exit; } // if (!$row) { // header("location: /BookStore/pages/newAuthor.php"); // exit; // } } // $name=$row["name"]; // } else { // // POST method: Update the data of the client // $id=$_POST["id"]; // $name=$_POST["name"]; // do { // if (empty($name)) { // $errorMessage='All the fields are required' ; // break; // } // // add new book to database // try { // $sql="UPDATE authors " . // "SET name ='$name' " . // "WHERE id = $id" ; // $result=$connection->query($sql);
      // header("location: /BookStore/pages/newAuthor.php");
      // } catch (mysqli_sql_exception $e) {
      // var_dump($e);
      // exit;
      // }




      // if (!$result) {
      // $errorMessage = "Invalid query: " . $connection->error;
      // break;
      // }
      // $successMessage = "Books updated correctly!";

      // header("location: /BookStore/pages/newAuthor.php");
      // exit;
      // } while (true);
      // }

      ?> -->
  </div>






  <?php
  include("../includes/footer.php");

  ?>

</body>

</html>