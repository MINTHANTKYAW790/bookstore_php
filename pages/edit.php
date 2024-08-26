<?php

require '../includes/session.php';

$servername = "localhost";
$username = "root";
$password = "";
$database = "wordwise";

//create connection

$connection = new mysqli($servername, $username, $password, $database);

$name = "";
$publishing_date = "";
$description = "";
$author_id = "";
$category_id = "";
$publishing_house_id = "";
$inserted_by = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // GET method: Show the data of the client
  if (!isset($_GET["id"])) {
    header("location: /BookStore/pages/admin.php");
    exit;
  }
  $id = $_GET["id"];
  //read the row of the selected client from database table
  $sql = "SELECT * FROM bookss WHERE id=$id";
  $result = $connection->query($sql);
  $row = $result->fetch_assoc();

  $sqlAuthor = "SELECT * FROM authors WHERE id=$row[author_id]";
  $resultAuthor = $connection->query($sqlAuthor);
  $rowAuthor = $resultAuthor->fetch_assoc();

  $sqlCategory = "SELECT * FROM category WHERE id=$row[category_id]";
  $resultCategory = $connection->query($sqlCategory);
  $rowCategory = $resultCategory->fetch_assoc();

  $sqlPbHouse = "SELECT * FROM publishing_house WHERE id=$row[publishing_house_id]";
  $resultPbHouse = $connection->query($sqlPbHouse);
  $rowPbHouse = $resultPbHouse->fetch_assoc();

  if (!$row) {
    header("location: /BookStore/pages/admin.php");
    exit;
  }
  $category_name = $rowCategory["category_name"];
  $pbhouse_name = $rowPbHouse["publishing_house_name"];
  $author_name = $rowAuthor["author_name"];
  $name = $row["name"];
  $author_id = $row["author_id"];
  $category_id = $row["category_id"];
  $publishing_house_id = $row["publishing_house_id"];
  $publishing_date = $row["publishing_date"];
  $description = $row["description"];
  $image = $row["image"];
  $pdf = $row["pdf"];
} else {
  // POST method: Update the data of the client

  $id = $_POST["id"];
  $name = $_POST["name"];
  $author_id = $_POST["author_id"];
  $category_id = $_POST["category_id"];
  $publishing_house_id = $_POST["publishing_house_id"];
  $publishing_date = $_POST["publishing_date"];
  $description = $_POST["description"];

  $file_name = $_FILES['image']['name'];
  $tempname = $_FILES['image']['tmp_name'];
  $imgfolder = '../img/' . $file_name;

  $pdf_file_name = $_FILES['pdf']['name'];
  $pdf_tempname = $_FILES['pdf']['tmp_name'];
  $pdffolder = '../pdf/' . $pdf_file_name;



  do {
    if (empty($name) || empty($author_id) || empty($category_id) || empty($description) || empty($publishing_date) || empty($publishing_house_id)) {
      $errorMessage = 'All the fields are required';
      break;
    }

    // add new book to database
    try {

      if ($file_name == "" and $pdf_file_name == "") {
        $sql = "UPDATE bookss " .
          "SET name ='$name', author_id ='$author_id',  category_id='$category_id', description='$description' ,publishing_date='$publishing_date',publishing_house_id='$publishing_house_id'" .
          "WHERE id = $id ";
      } else if ($file_name == "") {
        $sql = "UPDATE bookss " .
          "SET name ='$name', author_id ='$author_id',  category_id='$category_id', description='$description' ,publishing_date='$publishing_date',publishing_house_id='$publishing_house_id',  pdf = '$pdf_file_name' " .
          "WHERE id = $id ";
      } else if ($pdf_file_name == "") {
        $sql = "UPDATE bookss " .
          "SET name ='$name', author_id ='$author_id',  category_id='$category_id', description='$description' ,publishing_date='$publishing_date',publishing_house_id='$publishing_house_id', image = '$file_name'" .
          "WHERE id = $id ";
      } else {
        $sql = "UPDATE books " .
          "SET name ='$name', author_id ='$author_id',  category_id='$category_id', description='$description' ,publishing_date='$publishing_date',publishing_house_id='$publishing_house_id', image = '$file_name', pdf = '$pdf_file_name' " .
          "WHERE id = $id ";
      }

      $result = $connection->query($sql);
      if (move_uploaded_file($tempname, $imgfolder)) {
        echo "<h2>File uploaded successfully</h2>";
      } else {
        echo "<h2>File not uploaded!!!</h2>";
      }

      if (move_uploaded_file($pdf_tempname, $pdffolder)) {
        echo "<h2>File uploaded successfully</h2>";
      } else {
        echo "<h2>File not uploaded!!!</h2>";
      }
    } catch (mysqli_sql_exception $e) {
      var_dump($e);
      exit;
    }

    $file_name = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];
    $imgfolder = '../img/' . $file_name;

    $pdf_file_name = $_FILES['pdf']['name'];
    $pdf_tempname = $_FILES['pdf']['tmp_name'];
    $pdffolder = '../pdf/' . $pdf_file_name;

    if (!$result) {
      $errorMessage = "Invalid query: " . $connection->error;
      break;
    }
    $successMessage = "Books updated correctly!";

    header("location: /BookStore/pages/admin.php");
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
    <h2 class="orangeText">Update Book</h2>
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

    <form method="post" enctype="multipart/form-data" class="createForm">
      <input type="hidden" name="id" value="<?php echo $id; ?>"></input>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Book Name</label>
        <div class="col-sm-6">
          <input type="text" placeholder="Enter Book Title" class="form-control" name="name" value="<?php echo $name; ?>">
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Author</label>
        <div class="col-sm-6">
          <select name='author_id' id='author_id' class="form-select form-select-md mb-3" aria-label="Large select example">
            <option type="text" value="<?php echo $author_id; ?>"><?php echo $author_name; ?></option>
            <?php
            if ($connection->connect_error) {
              die("Connection failed!" . $connection->connect_error);
            }

            $sql = "SELECT * FROM authors";
            $result = $connection->query($sql);

            if (!$result) {
              die("Invalid Query!" . $connection->error);
            }

            while ($row = $result->fetch_assoc()) {
              echo "
                <option name='author_id' value='$row[id]' >$row[author_name]</option>
              ";
            }

            ?>
          </select>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Category</label>
        <div class="col-sm-6">
          <select name='category_id' id='category_id' class="form-select form-select-md mb-3" aria-label="Large select example">
            <option type="text" value="<?php echo $category_id; ?>"><?php echo $category_name; ?></option>
            <?php
            if ($connection->connect_error) {
              die("Connection failed!" . $connection->connect_error);
            }

            $sql = "SELECT DISTINCT * FROM category";
            $result = $connection->query($sql);

            if (!$result) {
              die("Invalid Query!" . $connection->error);
            }

            while ($row = $result->fetch_assoc()) {
              echo "

          <option name='category_id' value='$row[id]' >$row[category_name]</option>
         ";
            }

            ?>
          </select>
        </div>
      </div>


      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Publishing House</label>
        <div class="col-sm-6">
          <select name='publishing_house_id' id='publishing_house_id' class="form-select form-select-md mb-3" aria-label="Large select example">
            <option type="text" value="<?php echo $publishing_house_id; ?>"><?php echo $pbhouse_name; ?></option>
            <?php
            if ($connection->connect_error) {
              die("Connection failed!" . $connection->connect_error);
            }

            $sql = "SELECT DISTINCT * FROM publishing_house";
            $result = $connection->query($sql);

            if (!$result) {
              die("Invalid Query!" . $connection->error);
            }

            while ($row = $result->fetch_assoc()) {
              echo "

                <option name='publishing_house_id' value='$row[id]' >$row[publishing_house_name]</option>
              ";
            }

            ?>
          </select>
        </div>
      </div>



      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Publishing Date</label>
        <div class="col-sm-6">
          <input type="date" class="form-control" name="publishing_date" value="<?php echo $publishing_date; ?>">
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Description</label>
        <div class="col-sm-6">
          <textarea type="text" placeholder="Enter Book Description" class="form-control" name="description"><?php echo $description; ?></textarea>
        </div>
      </div>


      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Image File</label>
        <div class="col-sm-6">
          <input class="col-sm-6 form-control" type="file" name="image" value="<?php echo $image; ?>">
          <a href="../img/<?php echo $image; ?>" target="_blank" class="orangeText" style="text-decoration: underline;">Current Image</a>
        </div>

      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">PDF File</label>
        <div class="col-sm-6">
          <input class="col-sm-6 form-control" type="file" name="pdf" value="<?php echo $pdf; ?>">
          <a href="../img/<?php echo $pdf; ?>" target="_blank" class="orangeText" style="text-decoration: underline;">Current Pdf File</a>

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


  </div>

  <?php
  include("../includes/footer.php");
  ?>

</body>

</html>