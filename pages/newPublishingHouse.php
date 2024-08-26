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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $name = $_POST["name"];

  do {
    if (empty($name)) {
      $errorMessage = 'Name field must not be empty';
      break;
    }

    // add new book to database
    $sql = "INSERT INTO publishing_house (publishing_house_name) " .
      "VALUES ('$name')";
    $result = $connection->query($sql);

    if (!$result) {
      $errorMessage = "Invalid query: " . $connection->error;
      break;
    }

    $name = "";
    $errorMessage = "";
    $successMessage = "$name have been added correctly!";

    header("location: /BookStore/pages/newPublishingHouse.php");
    exit;
  } while (false);
}
require("../includes/head.php");
?>

<body>

  <?php
  include("../includes/navbar.php");
  include("../includes/pagination.php");
  ?>

  <div class="createContainer">
    <h2 style="padding:2% 0;" class="orangeText">Create New Publishing House</h2>
    <?php
    if (!empty($errorMessage)) {
      echo "
        <div class='alert alert-error alert-dismissible alert-warning fade show' role='alert'>
          <strong>$errorMessage</strong>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        ";
    }
    ?>

    <form method="post" enctype="multipart/form-data" class="authorForm">
      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Publishing House Name</label>
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
          <a href="../pages/admin.php" role="button" class="btn btn-danger">Exit</a>
        </div>
      </div>
    </form>

    <table class="table">
      <thead>
        <tr>
          <th>Publishing House ID</th>
          <th>Publishing House Name</th>
          <th>Inserted Time</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "wordwise";

        $connection = new mysqli($servername, $username, $password, $database);

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
        $stmt = $pdo->query("SELECT COUNT(*) FROM publishing_house");
        $totalpublishing_house = $stmt->fetchColumn();

        // Calculate the total number of pages
        $totalPages = ceil($totalpublishing_house / $booksPerPage);

        // Fetch the images for the current page
        $stmt = $pdo->prepare("SELECT * FROM publishing_house ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $booksPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $publishing_houses = $stmt->fetchAll();
        ?>

        <div class="gridContainer row row-cols-1 row-cols-sm-2 row-cols-md-5 row-cols-lg-5">
          <?php foreach ($publishing_houses as $publishing_house) :
            echo "
                  <tr>
                    <td>$publishing_house[id]</td>
                    <td>$publishing_house[publishing_house_name]</td>
                    <td>$publishing_house[inserted_time]</td>
                    <td>
                      <a href='../pages/edit_publishing_house.php?id=$publishing_house[id]' class='btn btn-primary'><i class='fa-solid fa-pen-to-square'></i></a>
                      </td>
                  </tr>
                  ";
          ?>
          <?php endforeach; ?>
        </div>
  </div>


  </tbody>
  </table>
  </div>

  <?php
  include("../includes/paginationBottom.php");
  include("../includes/footer.php");
  ?>

</body>

</html>