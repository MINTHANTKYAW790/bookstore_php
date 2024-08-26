<?php

require '../includes/session.php';

$servername = "localhost";
$username = "root";
$password = "";
$database = "wordwise";

//create connection

$connection = new mysqli($servername, $username, $password, $database);
$name = "";
$description = "";
$image = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // GET method: Show the data of the client
    if (!isset($_GET["id"])) {
        header("location: /BookStore/pages/newAuthor.php");
        exit;
    }
    $id = $_GET["id"];
    //read the row of the selected client from database table
    $sql = "SELECT * FROM authors WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();



    if (!$row) {
        header("location: /BookStore/pages/newAuthor.php");
        exit;
    }

    $name = $row["author_name"];
    $description = $row["description"];
    $image = $row["author_image"];
} else {
    // POST method: Update the data of the client

    $id = $_POST["id"];
    $name = $_POST["name"];
    $description = $_POST["description"];

    $file_name = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];
    $folder = '../authorImage/' . $file_name;


    do {
        if (empty($name) || empty($description)) {
            $errorMessage = 'All the fields are required';
            break;
        }

        // add new book to database
        try {

            if ($file_name == "") {
                $sql = "UPDATE authors " .
                    "SET author_name ='$name',description ='$description' " .
                    "WHERE id = $id";
            } else {
                $sql = "UPDATE authors " .
                    "SET author_name ='$name',description ='$description',author_image ='$file_name' " .
                    "WHERE id = $id";
            }

            $result = $connection->query($sql);
            if (move_uploaded_file($tempname, $folder)) {
                echo "<h2>File uploaded successfully</h2>";
            } else {
                echo "<h2>File not uploaded!!!</h2>";
            }
            header("location: /BookStore/pages/newAuthor.php");
        } catch (mysqli_sql_exception $e) {
            var_dump($e);
            exit;
        }

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }
        $successMessage = "Books updated correctly!";

        header("location: /BookStore/pages/newAuthor.php");
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
        <div class='alert alert-error alert-dismissible alert-warning fade show' role='alert'>
          <strong>$errorMessage</strong>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        ";
        }
        ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>"></input>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Author</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
            </div>


            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Image File</label>
                <div class="col-sm-6">
                    <input class="col-sm-6 form-control" type="file" name="image">
                    <a href="../img/<?php echo $image; ?>" target="_blank" class="orangeText" style="text-decoration: underline;">Current Image</a>

                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">About Author</label>
                <div class="col-sm-6">
                    <textarea type="text" class="form-control" name="description"><?php echo $description; ?></textarea>
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
                    <a href="../pages/newAuthor.php" role="button" class="btn btn-danger">Exit

                    </a>
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