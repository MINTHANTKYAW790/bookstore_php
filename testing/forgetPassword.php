<?php
require '../includes/session.php';

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
  <link rel="stylesheet" href="../CSS/adstyle.css">
</head>

<body>

  <?php
  include("../includes/navbar.php");
  include("../includes/pagination.php");
  ?>

  <div class="logInProcess">
    <h4 class="gridContainerTitle">forget Password</h4>
    <div class="loginContainer  row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2">
      <img src="../img/login.png" alt="">
      <div class="loginForm">
        <div class="insideLoginForm">
          <input type="email" class="form-control mb-3" id="exampleFormControlInput1" placeholder="Email">
          <div class="registerContainer row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 mb-3">
            <h6 class="text-black account">Don't you have an account? <a href="../pages/signUp.php" class="regtext">Sign Up</a></h6>
            <h6 class="forgetPassword"><a href="../pages/signIn.php" class="regtext">Sign In</a></h6>
          </div>
          <button type="button" class="btn btn-primary w-100">SEND RESET EMAIL</button>
          <!-- <h5>Or</h5>
        <button type="button" class="btn btn-primary w-100">Continue with google</button> -->
        </div>
      </div>
    </div>
  </div>

  <?php
  include("../includes/footer.php");

  ?>

</body>

</html>