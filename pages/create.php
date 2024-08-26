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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $file_name = $_FILES['image']['name'];
  $tempname = $_FILES['image']['tmp_name'];
  $imgfolder = '../img/' . $file_name;

  $pdf_file_name = $_FILES['pdf']['name'];
  $pdf_tempname = $_FILES['pdf']['tmp_name'];
  $pdffolder = '../pdf/' . $pdf_file_name;

  $name = $_POST["name"];
  $publishing_date = $_POST["publishing_date"];
  $description = $_POST["description"];
  $author_id = $_POST["author_id"];
  $category_id = $_POST["category_id"];
  $publishing_house_id = $_POST["publishing_house_id"];
  // $inserted_by = $_POST["inserted_by"];

  do {
    if (empty($name) || empty($author_id) || empty($category_id) || empty($description) || empty($publishing_date) || empty($publishing_house_id) || empty($file_name) || empty($pdf_file_name)) {
      $errorMessage = 'All the fields are required';
      break;
    }

    // add new book to database
    $sql = "INSERT INTO bookss (name,author_id,category_id,description,image,pdf,publishing_date,publishing_house_id) " .
      "VALUES ('$name','$author_id','$category_id','$description','$file_name','$pdf_file_name','$publishing_date','$publishing_house_id')";
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

    if (!$result) {
      $errorMessage = "Invalid query: " . $connection->error;
      break;
    }


    $name = "";
    $publishing_date = "";
    $description = "";
    $author_id = "";
    $category_id = "";
    $publishing_house_id = "";
    $inserted_by = "";
    $img = "";
    $pdf = "";
    $errorMessage = "";
    $publishing_house = "";
    $successMessage = "Books are added correctly!";

    header("location: /BookStore/pages/admin.php");
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


    <h2 class="orangeText newBook ">Insert New Book</h2>
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
            <option type="text" value="<?php echo $author_id; ?>">Choose Author Name</option>
            <?php

            $sql = "SELECT * FROM authors ORDER BY author_name ASC";
            $result = $connection->query($sql);

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
            <option type="text" value="<?php echo $category_id; ?>">Choose Category Name</option>
            <?php

            $sql = "SELECT DISTINCT * FROM category ORDER BY category_name ASC";
            $result = $connection->query($sql);

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
            <option type="text" value="<?php echo $publishing_house_id; ?>">Choose Publishing House Name</option>
            <?php

            $sql = "SELECT DISTINCT * FROM publishing_house ORDER BY publishing_house_name ASC";
            $result = $connection->query($sql);

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
          <textarea type="text" placeholder="Enter Book Description" class="form-control" name="description"></textarea>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Image File</label>
        <div class="col-sm-6">
          <input class="col-sm-6 form-control" type="file" name="image">
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">PDF File</label>
        <div class="col-sm-6">
          <input class="col-sm-6 form-control" type="file" name="pdf">
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