<div class="pagination paginationContainer ">
  <?PHP
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

    echo "
    <div id='pagination' class='pagination row row-cols-1 row-cols-xs-1 row-cols-sm-1 row-cols-md-4 row-cols-lg-4 text-center'>

";
  } else {
    echo "  <div id='pagination' class='pagination row row-cols-1 row-cols-xs-1 row-cols-sm-1 row-cols-md-4 row-cols-lg-5 text-center'>
    ";
  }
  ?>

  <a href="../pages/index.php" class="navMenu navIndex py-2">
    <h5>HOME</h5>
  </a>
  <a href="../pages/books.php" class="navMenu navBooks py-2">
    <h5>BOOKS</h5>
  </a>
  <a href="../pages/authors.php" class="navMenu navAuthors py-2">
    <h5>AUTHORS</h5>
  </a>
  <a href="../pages/categories.php" class="navMenu navCategories py-2">
    <h5>CATEGORIES</h5>
  </a>
  <?php
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

    echo "
    ";
  } else {
    echo "<a href='../pages/admin.php' class='navMenu navAdmin py-2'>
      <h5>ADMIN</h5>
    </a>";
  }
  ?>
</div>
</div>