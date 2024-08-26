<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "wordwise";

//create connection

$connection = new mysqli($servername, $username, $password, $database);


$email = "";
$password = "";
$phone = "";
$address = "";
$position = "";
$name = "";
$confirmPassword = "";

$image = "";


$errorMessage = "";
$successMessage = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $email = $_POST["email"];
  $password = $_POST["password"];
  $phone = $_POST["phone"];
  $address = $_POST["address"];
  $position = $_POST["position"];
  $name = $_POST["name"];
  $confirmPassword = $_POST["confirmPassword"];

  do {
    if (empty($email) || empty($password) || empty($phone) || empty($address) || empty($position) || empty($name)) {
      $errorMessage = 'All field must not be empty';
      break;
    }

    $selectSql = "SELECT * FROM admin";
    $selectResult = $connection->query($selectSql);
    while ($row = $selectResult->fetch_assoc()) {
      if ($email == $row['email']) {
        $errorMessage = "Email is already signed up";
        echo "This email cannot be register again";
      } else if ($password != $confirmPassword) {
        $errorMessage = "Password must be equal to Confirm Password";
      } else {
        $sql = "INSERT INTO admin (email,password,phone,address,position,name) " .
          "VALUES ('$email','$password','$phone','$address','$position','$name')";
        $result = $connection->query($sql);
        header("location: /BookStore/pages/signIn.php");
        if (!$result) {
          $errorMessage = "Invalid query: " . $connection->error;
          break;
        }
      }
    }

    $successMessage = "$name have been added correctly!";
  } while (false);
}
require("../includes/head.php");
?>

<body>

  <?php
  include("../includes/navbar.php");
  include("../includes/pagination.php");
  ?>

  <div class="logInProcess">
    <h1 class=" orangeText my-4">Sign Up</h1>
    <div class="loginContainer  row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2">
      <img src="../img/login.png" alt="">
      <div class="loginForm">
        <form class="insideLoginForm authorForm pt-2" method="post" enctype="multipart/form-data">
          <?php
          if (!empty($errorMessage)) {
            echo "
        <div class='alert alert-error alert-dismissible alert-warning fade show' role='alert' style='height:70px'>
          <strong>$errorMessage</strong>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        ";
          }
          ?>
          <input type=" text" id="" class="form-control mb-3" aria-describedby="passwordHelpBlock" placeholder="Full name" name="name" value="<?php echo $name; ?>">

          <input type="email" class="form-control mb-3" id="" placeholder="Email" name="email" value="<?php echo $email; ?>">
          <input type=" phone" id="" class="form-control mb-3" aria-describedby="passwordHelpBlock" placeholder="Enter phone number" name="phone" value="<?php echo $phone; ?>">
          <input type=" text" class="form-control mb-3" id="" placeholder="Address" name="address" value="<?php echo $address; ?>">
          <input type=" text" class="form-control mb-3" id="" placeholder="Position" name="position" value="<?php echo $position; ?>">
          <input type="password" class="form-control mb-3" id="" placeholder="Password" name="password" value="<?php echo $password; ?>">

          <input type="password" id="" class="form-control mb-3" aria-describedby="passwordHelpBlock" placeholder="Confirm password" name="confirmPassword" value="<?php echo $confirmPassword; ?>">
          <div class="registerContainer row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 mb-3">
            <h6 class="text-black account">Already have an account? <a href="../pages/signIn.php" class="regtext">Sign in</a></h6>
            <h6 class="forgetPassword"><a href="../pages/forgetPassword.php" class="regtext">Forget Password?</a></h6>
          </div>
          <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        </form>
      </div>
    </div>
  </div>

  <?php
  include("../includes/footer.php");

  ?>

</body>

</html>