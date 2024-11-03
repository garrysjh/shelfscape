<?php
session_start();
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
$limit = 9; // Number of books per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Retrieve search query and category filter
$query = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Modify SQL query based on search query and category filter
if ($query) {
    $sql = "SELECT COUNT(*) as total FROM Books WHERE title LIKE ? OR isbn LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} elseif ($category) {
    $sql = "SELECT COUNT(*) as total FROM Books WHERE genres LIKE ?";
    $stmt = $conn->prepare($sql);
    $categoryTerm = '%' . $category . '%';
    $stmt->bind_param("s", $categoryTerm);
} else {
    $sql = "SELECT COUNT(*) as total FROM Books";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$totalBooks = $result->fetch_assoc()['total'];
$totalPages = ceil($totalBooks / $limit);

if ($query) {
    $sql = "SELECT bookId, title, author, coverImg FROM Books WHERE title LIKE ? OR isbn LIKE ? LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
} elseif ($category) {
    $sql = "SELECT bookId, title, author, coverImg FROM Books WHERE genres LIKE ? LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $categoryTerm, $limit, $offset);
} else {
    $sql = "SELECT bookId, title, author, coverImg FROM Books LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

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
            <a href="index.php">
          <img src="assets/icons/shelfscape-logo.png" alt="Shelfscape Logo" />
        </a>
        </div>
        <div class="nav-links">
          <a href="books.php">Books</a>
          <div class="dropdown">
            <a href="#">Categories</a>
            <div class="dropdown-content">
              <a href="books.php?category=Fantasy">Fantasy</a>
              <a href="books.php?category=Fiction">Fiction</a>
              <a href="books.php?category=Romance">Romance</a>
              <a href="books.php?category=Classics">Classics</a>
              <a href="books.php?category=Horror">Horror</a>
            </div>
          </div>
          <a href="events.php">Events</a>
          <a href="aboutus.php">About</a>
          <a href="donate.php">Donate</a>
        </div>
        <div class="search-bar">
          <form action="books.php" method="GET">
            <input type="text" name="query" placeholder="ENTER SERIAL NO OR TITLE" />
            <button type="submit" class="search-button">Search</button>
            <i class="fas fa-search"></i>
          </form>
        </div>
        <div class="account-icon">
          <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
            <div class="dropdown">
            <img src="<?php echo $_SESSION['profilePicture']; ?>" alt="User Icon" class="usericon"/>
              <div class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php">Logout</a>
              </div>
            </div>
          <?php else: ?>
            <a href="login.php">
              <img src="assets/icons/user.png" alt="User Icon" class="usericon"/>
            </a>
          <?php endif; ?>
        </div>
      </nav>
    </header>
    <main>
      <div class="main-container">
        <h1>All Books</h1>
                <!-- Pagination Links -->
                <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&query=<?php echo urlencode($query); ?>&category=<?php echo urlencode($category); ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&query=<?php echo urlencode($query); ?>&category=<?php echo urlencode($category); ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>&query=<?php echo urlencode($query); ?>&category=<?php echo urlencode($category); ?>">Next</a>
            <?php endif; ?>
        </div>
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
                <a href="?page=<?php echo $page - 1; ?>&query=<?php echo urlencode($query); ?>&category=<?php echo urlencode($category); ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&query=<?php echo urlencode($query); ?>&category=<?php echo urlencode($category); ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>&query=<?php echo urlencode($query); ?>&category=<?php echo urlencode($category); ?>">Next</a>
            <?php endif; ?>
        </div>
        </div>
    </main>
  </body>
  <script></script>
</html>
