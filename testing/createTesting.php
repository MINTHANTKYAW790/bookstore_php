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

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Word Wise</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/ff5868ab46.js" crossorigin="anonymous"></script>
  <link rel="icon" type="image/x-icon" href="../img/wordwise.png">
  <link rel="stylesheet" href="../CSS/style.css">
  <link rel="stylesheet" href="../CSS/adstyle.css">

</head>

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
          <input class="form-control col-sm-6" type="text" id="author" name="author_id">
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script>
          $(function() {
            $("#author").autocomplete({
              source: function(request, response) {
                $.ajax({
                  class: "form-control",
                  url: "../testing/search_authors.php",
                  type: "POST",
                  data: {
                    term: request.term
                  },
                  dataType: "json",
                  success: function(data) {
                    response(data);
                  }
                });
              },
              minLength: 2
            });
          });
        </script>

      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Category</label>
        <div class="col-sm-6">
          <input class="form-control col-sm-6" type="text" id="category" name="category_id">
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script>
          $(function() {
            $("#category").autocomplete({
              source: function(request, response) {
                $.ajax({
                  class: "form-control",
                  url: "../testing/search_category.php",
                  type: "POST",
                  data: {
                    term: request.term
                  },
                  dataType: "json",
                  success: function(data) {
                    response(data);
                  }
                });
              },
              minLength: 2
            });
          });
        </script>

      </div>


      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Publishing House</label>
        <div class="col-sm-6">
          <input class="form-control col-sm-6" type="text" id="publishing_house" name="publishing_house_id">
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script>
          $(function() {
            $("#publishing_house").autocomplete({
              source: function(request, response) {
                $.ajax({
                  class: "form-control",
                  url: "../testing/search_publishing_houses.php",
                  type: "POST",
                  data: {
                    term: request.term
                  },
                  dataType: "json",
                  success: function(data) {
                    response(data);
                  }
                });
              },
              minLength: 2
            });
          });
        </script>

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
          <input type="text" placeholder="Enter Book Description" class="form-control" name="description" value="<?php echo $description; ?>">
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
          <a href="../pages/admin.php" role="button" class="btn btn-danger">Cancel</a>
        </div>
      </div>
    </form>


  </div>

  <?php
  include("../includes/footer.php");
  ?>

</body>

</html>