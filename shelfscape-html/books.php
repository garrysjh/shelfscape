<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shelfscape";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination settings
$limit = 6; // Number of books per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Retrieve total number of books
$sql = "SELECT COUNT(*) as total FROM Books";
$result = $conn->query($sql);
$totalBooks = $result->fetch_assoc()['total'];
$totalPages = ceil($totalBooks / $limit);

// Retrieve books for the current page
$sql = "SELECT bookId, title, author, coverImg FROM Books LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    // Fetch all books
    while($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
} else {
    echo "No books found.";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Shelfscape</title>
    <meta charset="utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles/reset.css" />
    <link rel="stylesheet" href="styles/books.css" />
  </head>
  <body>
    <header>
      <nav class="navbar">
        <div class="logo">
            <a href="index.html">
          <img src="assets/icons/shelfscape-logo.png" alt="Shelfscape Logo" />
        </a>
        </div>
        <div class="nav-links">
          <a href="books.php">Books</a>
          <div class="dropdown">
            <a href="#">Categories</a>
            <div class="dropdown-content">
              <a href="#">Fantasy</a>
              <a href="#">Product 2</a>
              <a href="#">Product 3</a>
            </div>
          </div>
          <a href="#">About</a>
          <a href="#">Services</a>

          <a href="#">Contact</a>
        </div>
        <div class="search-bar">
          <input type="text" placeholder="ENTER SERIAL NO OR TITLE" />
          <button class="search-button">Search</button>
          <i class="fas fa-search"></i>
        </div>
        <div class="account-icon">
          <a href="login.html">
            <img src="assets/icons/user.png" alt="User Icon" />
          </a>
        </div>
        
      </nav>
    </header>
    <main>
      <div class="main-container">
        <h1>All Books</h1>
        <div class="books-container">
            <?php foreach ($books as $book): ?>
              <div class="book">
                    <a href="book.php?id=<?php echo $book['bookId']; ?>">
                        <img src="<?php echo $book['coverImg']; ?>" alt="<?php echo $book['title']; ?> Cover Image">
                        <p class="title"><?php echo $book['title']; ?></p>
                        <p class="author"><?php echo $book['author']; ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Pagination Links -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>
        </div>
    </main>
  </body>
  <script></script>
</html>
