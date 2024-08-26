<?php
require '../includes/session.php';
require("../includes/head.php");
?>


<body>

  <?php
  include("../includes/navbar.php");
  include("../includes/pagination.php");
  ?>

  <div class="logInProcess">

    <div class="buttonList row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-2">
      <div class="leftButtonList">
        <!-- <h4>If it is new author, add new author name here!</h4> -->
        <a href="../pages/newAuthor.php" class="btn btn-orange">AUTHOR</a>

        <!-- <h4>If it is new category, add new category here!</h4> -->
        <a href="../pages/newCategory.php" class="btn btn-orange">CATEGORY</a>

        <a href="../pages/newPublishingHouse.php" class="btn btn-orange">PUBLISHING HOUSE</a>
      </div>
      <div class="rightButtonList">
        <a href="../pages/create.php" class="btn btn-orange">NEW BOOK</a>
      </div>
    </div>

    <!-- <h4>This is in the admin Dashboard</h4> -->


    <table class="table">
      <thead>
        <tr>

          <th>BookID</th>

          <th>Title</th>
          <th>Author</th>
          <th>Category</th>
          <th>Publishing House</th>

          <th>Publication Date</th>
          <th width="130px">Created at</th>
          <th width="150px">Action</th>

        </tr>
      </thead>
      <tbody>


        <!-- PHP code started from here!!! -->
        <?php

        // <!-- PHP code started from here!!! -->


        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "wordwise";

        $connection = new mysqli($servername, $username, $password, $database);


        // <!-- PAGINATION BUTTONS PROCESS -->
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
        $stmt = $pdo->query("SELECT COUNT(*) FROM bookss");
        $totalBooks = $stmt->fetchColumn();

        // Calculate the total number of pages
        $totalPages = ceil($totalBooks / $booksPerPage);

        // Fetch the images for the current page
        $stmt = $pdo->prepare("SELECT * FROM bookss ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $booksPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $books = $stmt->fetchAll();

        ?>
        <!-- </div> -->
        <div class="gridContainer row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5">

          <?php $i = 1;
          foreach ($books as $row) :

            $authorSql = "SELECT * FROM authors WHERE $row[author_id] = authors.id";
            $authorResult = $connection->query($authorSql);
            $author = $authorResult->fetch_assoc();

            $categorySql = "SELECT * FROM category WHERE $row[category_id] = category.id";
            $categoryResult = $connection->query($categorySql);
            $category = $categoryResult->fetch_assoc();

            $phouseSql = "SELECT * FROM publishing_house WHERE $row[publishing_house_id] = publishing_house.id";
            $phouseResult = $connection->query($phouseSql);
            $phouse = $phouseResult->fetch_assoc();


            echo "
            <tr>
           
            <td>$row[id]</td>

            <td>$row[name]</td>
            <td>$author[author_name]</td>
            <td>$category[category_name]</td>
            <td>$phouse[publishing_house_name]</td>

            <td>$row[publishing_date]</td>
            <td>$row[timestamp]</td>
            <td><a href='../pages/detail.php?id=$row[id]' class='btn btn-info btn-sm'><i class='fa-solid fa-eye'></i></a>
            <a href='../pages/edit.php?id=$row[id]' class='btn btn-primary btn-sm'><i class='fa-solid fa-pen-to-square'></i></a>
            <a class='btn btn-danger btn-sm delete-btn' data-id='$row[id]' data-toggle='modal' data-target='#deleteModal'><i class='fa-solid fa-trash'></i></a>


            <div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
              <div class='modal-content'>
                <div class='modal-header'>
                <h1 class='modal-title fs-5' id='exampleModalLabel'>Delete</h1>
                  
                </div>
                
                <form action='deleteTesting.php' method='post'>
                  <div class='modal-body'>
                    Are you sure you want to delete this ID?
                    <input type='text' name='id' id='delete-id'>
                  </div>
                  <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                    <button type='submit' class='btn btn-danger'>Delete</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        
          
            </td>
            </tr>
        ";

            $i++;
          ?>
          <?php endforeach; ?>


        </div>
      </tbody>

    </table>

  </div>
  <script>
    $(document).ready(function() {
      $('.delete-btn').on('click', function() {
        var id = $(this).data('id');
        $('#delete-id').val(id);
      });
    });
  </script>


  <!-- PAGINATION DIV -->
  <div class=" paginationNum row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3">
    <div>
      <?php if ($page > 1) : ?>
        <a href="?page=<?php echo $page - 1; ?>" style="color:black" class="btn btn-orange">&laquo; Previous</a>
      <?php endif; ?>
    </div>

    <div style="text-align: center;"><?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?> btn btn-orange" style="color:black" class="">
          <?php echo $i; ?>
        </a>
      <?php endfor; ?>
    </div>

    <div style="text-align: right;">
      <?php if ($page < $totalPages) : ?>
        <a href="?page=<?php echo $page + 1; ?>" style="color:black" class="btn btn-orange">Next &raquo;</a>
      <?php endif; ?>
    </div>
  </div>
  <!-- END OF PAGINATION DIV -->




  <?php
  include("../includes/footer.php");

  ?>
  <script src="../JavaScript//script.js"></script>
</body>

</html>