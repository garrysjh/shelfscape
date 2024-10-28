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

// Get book ID from URL parameter
$bookId = $_GET['id'];

// Retrieve book details
$sql = "SELECT title, author, coverImg, isbn, description, genres FROM Books WHERE bookId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();

$book = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <title><?php echo $book['title']; ?></title>
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="styles/book.css">
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
    <div class="book-intro">
        <img src="<?php echo $book['coverImg']; ?>" alt="<?php echo $book['title']; ?> Cover Image">
        <div class="book-description">
        <h1><?php echo $book['title']; ?></h1>
        <p><strong>Author: </strong><?php echo $book['author'];?></p>
        <p><strong>ISBN: </strong><?php echo $book['isbn'];?></p>
        <p><strong>Genres: </strong><?php echo join(', ', json_decode($book['genres'], true));?></p>
        <p><?php echo $book['description']; ?></p>
        </div>
</div>
    </main>
</body>
</html>