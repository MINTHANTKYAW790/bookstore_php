<!-- PAGINATION BUTTONS PROCESS -->

<?php
// Database connection
$servername = 'localhost';
$database = 'wordwise';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$servername;dbname=$database";
$connection = new mysqli($servername, $user, $pass, $database);

$options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

// Define the number of books per page
$booksPerPage = 10;

// Get the current page number from the query string (default to 1 if not set)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $booksPerPage;

// Fetch the total number of books
$stmt = $pdo->query("SELECT COUNT(*) FROM bookss");
$totalBooks = $stmt->fetchColumn();

// Calculate the total number of pages
$totalPages = ceil($totalBooks / $booksPerPage);

// Fetch the books for the current page, ordered by the latest inserted
$stmt = $pdo->prepare("SELECT * FROM bookss ORDER BY timestamp DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $booksPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$books = $stmt->fetchAll();

$sql = "SELECT * FROM bookss";
$result = $connection->query($sql);

?>

<body>

  <div class="gallery">
    <?php foreach ($books as $book) :
      $authorSql = "SELECT * FROM authors WHERE $book[author_id] = authors.id";
      $authorResult = $connection->query($authorSql);
      $author = $authorResult->fetch_assoc();
    ?>
      <div class="book">
        <h2><?php echo htmlspecialchars($book['name']); ?></h2>
        <p><?php echo htmlspecialchars($author['author_name']); ?></p>

      </div>
    <?php endforeach; ?>
  </div>

  <div class="pagination">
    <?php if ($page > 1) : ?>
      <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
      <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
        <?php echo $i; ?>
      </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages) : ?>
      <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
    <?php endif; ?>
  </div>

</body>

</html>