<?php session_start();
require("../includes/head.php"); 
?>

<body>

  <?php

  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "wordwise";

  //create connection

  $connection = new mysqli($servername, $username, $password, $database);


  $email = "";
  $password = "";

  $errorMessage = "";
  $successMessage = "";
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST["email"];
    $password = $_POST["password"];

    do {
      if (empty($email) || empty($password)) {
        $errorMessage = 'All fields must not be empty';
        break;
      }

      // add new book to database
      $sql = "SELECT * FROM admin";
      $result = $connection->query($sql);
      while ($row = $result->fetch_assoc()) {


        if ($row['email'] == $email && $row['password'] == $password) {
          $_SESSION['sessionId'] = $row['id'];
          $_SESSION['sessionUsername'] = $row['name'];
          $_SESSION['loggedin'] = true;

          header("location: /BookStore/pages/admin.php?name=$row[name]");
        } else {
          $errorMessage = "Email or Password is wrong";
        }
      }
      if (!$result) {
        $errorMessage = "Invalid query: " . $connection->error;
        break;
      }

      $email = "";
      $password = "";
      $errorMessage = "Email or Password is wrong";
    } while (false);
  }

  include("../includes/navbar.php");
  include("../includes/pagination.php");
  ?>

  <div class="logInProcess">
    <h1 class=" orangeText my-4">Sign In</h1>
    <div class="loginContainer  row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2">
      <img src="../img/login.png" alt="">
      <form class="loginForm authorForm" action="../pages/signIn.php" method="post" enctype="multipart/form-data" id="#signInForm">
        <?php
        if (!empty($errorMessage)) {
          echo "
        <div class='alert alert-error alert-dismissible alert-warning fade show' role='alert' style='height:70px;'>
          <strong>$errorMessage</strong>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        ";
        }
        ?>
        <div class="insideLoginForm">


          <input type="email" class="form-control mb-3" id="signInEmail" placeholder="Email" name="email" value="<?php echo $email; ?>">
          <input type="password" id="signInPassword" class="form-control mb-3" aria-describedby="passwordHelpBlock" placeholder="Enter password" name="password" value="<?php echo $password; ?>">
          <div class="registerContainer row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 mb-3">
            <h6 class="text-black account">Don't you have an account? <a href="../pages/signUP.php" class="regtext">Sign
                Up</a></h6>
            <h6 class="forgetPassword"><a href="../pages/forgetPassword.php" class="regtext">Forget Password?</a></h6>
          </div>
          <button type="submit" class="btn btn-orange w-100">Sign In</button>
        </div>
      </form>
    </div>
  </div>
  <?php
  include("../includes/footer.php");

  ?>
</body>

</html>