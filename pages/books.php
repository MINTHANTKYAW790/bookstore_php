<?php
session_start();

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
  <link rel="stylesheet" href="../CSS/bstyle.css">
</head>

<body>

  <?php
  include("../includes/navbar.php");
  include("../includes/pagination.php");
  ?>

  <!-- This is in the display period of the BOOKS page -->
  <h4 class="booksText">BOOKS LIST</h4>

  <!-- PHP code started from here!!! -->
  <?php

  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "wordwise";

  $connection = new mysqli($servername, $username, $password, $database);


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
  $stmt = $pdo->query("SELECT COUNT(*) FROM bookss ");
  $totalBooks = $stmt->fetchColumn();

  // Calculate the total number of pages
  $totalPages = ceil($totalBooks / $booksPerPage);

  // Fetch the images for the current page
  $stmt = $pdo->prepare("SELECT * FROM bookss ORDER BY name ASC LIMIT :limit OFFSET :offset");
  $stmt->bindValue(':limit', $booksPerPage, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  $books = $stmt->fetchAll();


  // <!-- END OF PAGINATION PROCESS -->

  if ($connection->connect_error) {
    die("Connection failed!" . $connection->connect_error);
  }

  $sql = "SELECT * FROM bookss";
  $result = $connection->query($sql);

  if (!$result) {
    die("Invalid Query!" . $connection->error);
  }

  while ($row = $result->fetch_assoc()) {

    $authorSql = "SELECT * FROM authors WHERE $row[author_id] = authors.id";
    $authorResult = $connection->query($authorSql);
    $author = $authorResult->fetch_assoc();
  }

  ?>
  <!-- </div> -->
  <div class="gridContainer row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5">

    <?php foreach ($books as $book) : ?>

      <!-- <img src="../img/<?php echo htmlspecialchars($book['image']); ?>" alt="Image" width="100" height="100"> -->

      <div class="imageBox ">
        <a href="../pages/detail.php?id=<?php echo htmlspecialchars($book['id']); ?>">
          <img class="imageImageBox" src="../img/<?php echo htmlspecialchars($book['image']); ?>" alt="image" height="309px" width="224px" />
          <div class=" title"><i class="fa-solid fa-magnifying-glass"></i>
          </div>

          <p style="color:black;" class="authorIndex"><?php echo htmlspecialchars($author['author_name']); ?></p>
          <p style="color:black" class="statusIndex"><?php echo htmlspecialchars($book['name']); ?></p>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <?php
  include('../includes/paginationBottom.php');
  include("../includes/footer.php");

  ?>

</body>

</html>