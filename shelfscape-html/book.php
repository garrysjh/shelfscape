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

// Get book ID from URL parameter
$bookId = $_GET['id'];

// Retrieve book details
$sql = "SELECT title, author, coverImg, isbn, description, genres FROM Books WHERE bookId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();

$book = $result->fetch_assoc();

// Retrieve 3 most recent reviews for the book
$sql = "SELECT u.username, u.profilePicture, r.rating, r.review, r.date, r.recommended FROM reviews r LEFT JOIN user u on r.userId = u.id WHERE bookId = ? ORDER BY r.date DESC LIMIT 3";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}
$stmt->close();
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
          <a href="events.html">Events</a>
          <a href="aboutus.html">About</a>
          <a href="donate.html">Donate</a>
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
            <img src="assets/icons/user.png" alt="User Icon" class="usericon"/>
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
    <div class="book-intro">
        <img src="<?php echo $book['coverImg']; ?>" alt="<?php echo $book['title']; ?> Cover Image">
        <div class="book-description">
        <h1><?php echo $book['title']; ?></h1>
        <p><strong>Author: </strong><?php echo $book['author'];?></p>
        <p><strong>ISBN: </strong><?php echo $book['isbn'];?></p>
        <p><strong>Genres: </strong><?php echo $book['genres'] ? join(', ', json_decode($book['genres'], true)) : 'No genre specified'; ?></p>
        <p><?php echo $book['description']; ?></p>
        </div>
</div>
    </main>
    <section class="review-section">
      <div class="recent-reviews">
        <h2>Recent Reviews</h2>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <div class="review-title">
                      <div class="review-profile">
                      <img src="<?php echo $review['profilePicture']; ?>" alt="User Icon" class="otherusericon"/>
                      <p><strong><?php echo htmlspecialchars($review['username']); ?></strong></p>
            </div>
                      <p class="review-date"><em><?php echo htmlspecialchars($review['date']); ?></em></p>
                    </div>
                    <p class="review-rating"><strong>Rating given: </strong><?php echo htmlspecialchars($review['rating']); ?>/5</p>
                    <p class="review-content"><strong>Review: </strong></br></p>
                    <p class="review-content"><?php echo htmlspecialchars($review['review']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="review-content" >No reviews found.</p>
        <?php endif; ?>
        </div>
    </section>
</body>
</html>