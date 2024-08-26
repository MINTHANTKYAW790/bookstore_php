<?php

require '../includes/session.php';

$servername = "localhost";
$username = "root";
$password = "";
$database = "wordwise";

//create connection

$connection = new mysqli($servername, $username, $password, $database);
$name = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // GET method: Show the data of the client
  if (!isset($_GET["id"])) {
    header("location: /BookStore/pages/newPublishingHouse.php");
    exit;
  }
  $id = $_GET["id"];
  //read the row of the selected client from database table
  $sql = "SELECT * FROM publishing_house WHERE id=$id";
  $result = $connection->query($sql);
  $row = $result->fetch_assoc();

  if (!$row) {
    header("location: /BookStore/pages/newPublishingHouse.php");
    exit;
  }
  $name = $row["publishing_house_name"];
} else {
  // POST method: Update the data of the client

  $id = $_POST["id"];
  $name = $_POST["name"];


  do {
    if (empty($name)) {
      $errorMessage = 'All the fields are required';
      break;
    }

    // add new book to database
    try {
      $sql = "UPDATE publishing_house " .
        "SET publishing_house_name ='$name' " .
        "WHERE id = $id";
      $result = $connection->query($sql);
      header("location: /BookStore/pages/newPublishingHouse.php");
    } catch (mysqli_sql_exception $e) {
      var_dump($e);
      exit;
    }

    if (!$result) {
      $errorMessage = "Invalid query: " . $connection->error;
      break;
    }
    $successMessage = "Books updated correctly!";

    header("location: /BookStore/pages/newPublishingHouse.php");
    exit;
  } while (true);
}
require("../includes/head.php");
?>

<body>

  <?php
  include("../includes/navbar.php");
  include("../includes/pagination.php");
  ?>
  
  <div class="createContainer">

    <h2 class="orangeText">Edit Author</h2>
    <?php
    if (!empty($errorMessage)) {
      echo "
        <div class='alert alert-error alert-dismissible fade show' role='alert'>
          <strong>$errorMessage</strong>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        ";
    }
    ?>

    <form method="post">
      <input type="hidden" name="id" value="<?php echo $id; ?>"></input>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Publishing House</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
        </div>
      </div>


      <?php
      if (!empty($successMessage)) {
        echo "
          <div class='alert alert-success alert-dismissible fade show' role='alert'>
          <strong>$successMessage</strong>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
          ";
      }
      ?>
      <div class="row mb-3">
        <div class="offset-sm-3 col-sm-3 d-grid">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
        <div class="col-sm-3 d-grid">
          <a href="../pages/newPublishingHouse.php" role="button" class="btn btn-danger">Exit</a>
        </div>
      </div>
    </form>
  </div>
  <?php
  include("../includes/footer.php");

  ?>
  <script src="../JavaScript//script.js"></script>
</body>

</html>