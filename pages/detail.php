<?php
session_start();
require("../includes/head.php");
?>

<body>

  <?php
  include("../includes/navbar.php");
  include("../includes/pagination.php");

  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "wordwise";

  //create connection

  $connection = new mysqli($servername, $username, $password, $database);

  $status = "";
  $author = "";
  $category = "";
  $description = "";
  $image = "";
  $pdf = "";
  $errorMessage = "";
  $successMessage = "";

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // GET method: Show the data of the client
    if (!isset($_GET["id"])) {
      header("location: /BookStore/pages/index.php");
      exit;
    }
    $id = $_GET["id"];
    //read the row of the selected client from database table
    $sql = "SELECT * FROM bookss WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
      header("location: /BookStore/pages/index.php");
      exit;
    }
    $name = $row["name"];
    $author_id = $row["author_id"];
    $category_id = $row["category_id"];
    $description = $row["description"];
    $image = $row["image"];
    $pdf = $row["pdf"];
  } else {
    // POST method: Update the data of the client

    $id = $_POST["id"];
    $name = $_POST["name"];
    $author_id = $_POST["author_id"];
    $category_id = $_POST["category_id"];
    $description = $_POST["description"];
    $image = $_POST["image"];
    $pdf = $_POST["pdf"];

    do {
      if (empty($name) || empty($author_id) || empty($category_id) || empty($description) || empty($image)) {
        $errorMessage = 'All the fields are required';
        break;
      }

      // add new book to database
      if (!$result) {
        $errorMessage = "Invalid query: " . $connection->error;
        break;
      }
      $successMessage = "Books updated correctly!";

      header("location: /BookStore/pages/admin.php");
      exit;
    } while (true);
  }
  ?>
  <?php
  $authorSql = "SELECT * FROM authors WHERE $author_id = authors.id";
  $authorResult = $connection->query($authorSql);
  $author = $authorResult->fetch_assoc();

  $categorySql = "SELECT * FROM category WHERE $row[category_id] = category.id";
  $categoryResult = $connection->query($categorySql);
  $category = $categoryResult->fetch_assoc();

  $phouseSql = "SELECT * FROM publishing_house WHERE $row[publishing_house_id] = publishing_house.id";
  $phouseResult = $connection->query($phouseSql);
  $phouse = $phouseResult->fetch_assoc();
  ?>

  <!-- Former detail page format -->
  <div class="detailContainer  row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-2">
    <div class="imageContainer" style="width: 30%;">
      <img src="../img/<?php echo $image ?>" alt="image" width=" 352px" height="485px">
    </div>
    <div style="width: 60%;" class="detailTextContainer">
      <h5 class="detailText author"><?php echo $author['author_name'] ?></h5>
      <h5 class="detailText status"><?php echo $name ?></h5>
      <h6 class="detailText category"><?php echo $category['category_name'] . "<br><br>" ?></h6>
      <p class="detailText desciption"><?php echo $description ?></p>
      <div class="detailButton">
        <a href="../pdf/<?php echo $pdf ?>" download><button type="button" class="btn btn-orange">Download <i class="fa-solid fa-circle-down"></i></button></a>
        <a href="../pdf/<?php echo $pdf ?>" target="_blank"><button type="button" class="btn btn-orange">Read Online <i class="fa-solid fa-eye"></i></button></a>
      </div>
    </div>
  </div>

  <?php
  include("../includes/footer.php");
  ?>
</body>

</html>