<?php
require '../includes/session.php';

?>
<?php
if (isset($_GET["id"])) {
  $id = $_GET["id"];

  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "wordwise";

  //create connection

  $connection = new mysqli($servername, $username, $password, $database);

  $sql = "DELETE FROM bookss WHERE id =$id";
  $connection->query($sql);
}
header("location: /BookStore/pages/admin.php");
exit;
?>