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

// Retrieve all books
$sql = "SELECT title, coverImg FROM Books";
$result = $conn->query($sql);

$bookNames = [];
if ($result->num_rows > 0) {
    // Fetch all book names
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
          <a href="#">Books</a>
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
        <h1>Books</h1>
        <div class="books-container">
            <?php foreach ($books as $book): ?>
                <div class="book">
                    <img src="<?php echo $book['coverImg']; ?>" alt="<?php echo $book['title']; ?> Cover Image">
                    <p><?php echo $book['title']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
  </body>
  <script></script>
</html>
