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

$defaultBookId = "10210.Jane_Eyre"; // Change this to your desired book ID
$bookId = isset($_GET['id']) ? $_GET['id'] : $defaultBookId;


// Retrieve book details
$sql = "SELECT title, author, coverImg, isbn, description, genres FROM Books WHERE bookId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();

$book = $result->fetch_assoc();

// Retrieve 3 most recent reviews for the book
$sql = "SELECT u.id, u.username, u.profilePicture, r.rating, r.review, r.date, r.recommended FROM reviews r LEFT JOIN user u on r.userId = u.id WHERE bookId = ? ORDER BY r.date DESC LIMIT 3";
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
    <link rel="icon" type="image/x-icon" href="./assets/icons/shelfscape-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <title>Shelfscape: <?php echo $book['title']; ?></title>
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="styles/book.css">
</head>
<body>
<!-- Full-width Header -->
<header>
        <nav class="navbar">
            <div class="logo">
                <a href="index.php">
                    <img class="logo-homepage" src="assets/icons/shelfscape-logo.png" alt="Shelfscape Logo" />
                </a>
            </div>
            <div class="nav-links">
                <a href="books.php">Books</a>
                <div class="dropdown">
                    <a href="#">Categories ▼</a>
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
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                    <a href="feed.php">Feed</a>
                <?php endif; ?>
            </div>
            <div class="search-bar">
                <form action="books.php" method="GET">
                    <input type="text" name="query" placeholder="ENTER SERIAL NO OR TITLE" required/>
                    <button type="submit" class="search-button">
                        <img class="search-button-img" src="assets/icons/search.png" alt="Search Icon" />
                    </button>
                </form>
            </div>
            <div class="account-icon">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                    <div class="dropdown">
                        <img src="<?php echo $_SESSION['profilePicture']; ?>" alt="User Icon" class="usericon"/>
                        <div class="dropdown-content login-dropdown-content">
                            <a href="profile.php?id=<?php echo htmlspecialchars($_SESSION['user_id']); ?>">Profile</a>
                            <a href="friends.php">Friends</a>
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
                        <a href="profile.php?id=<?php echo $review['id']; ?>">
                      <img src="<?php echo $review['profilePicture']; ?>" alt="User Icon" class="otherusericon"/>
                      </a>
                      <a href="profile.php?id=<?php echo $review['id']; ?>">
                      <p><strong><?php echo htmlspecialchars($review['username']); ?></strong></p>
                      </a>
            
            </div>
                      <p class="review-date"><em><?php echo htmlspecialchars($review['date']); ?></em></p>
                    </div>
                    <p class="review-rating"><strong>Rating given: </strong><?php echo htmlspecialchars($review['rating']); ?>/5</p>
                    <p class="review-content"><strong>Review: </strong></br></p>
                    <p class="review-content"><?php echo htmlspecialchars($review['review']); ?></p>
                </div>
            <?php endforeach; ?>
            <a href="all_reviews.php?bookId=<?php echo $bookId; ?>" class="all-reviews">View all reviews</a>
        <?php else: ?>
            <p class="review-content" >No reviews found.</p>
        <?php endif; ?>
        </div>
    </section>
    <section class="write-review-section">
          <div class="write-review-div">
            <h2>Leave a Review</h2>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
              <form action="submit_review.php" method="POST">
                <input type="hidden" name="bookId" value="<?php echo $bookId; ?>">
                <input type="hidden" name="userId" value="<?php echo $_SESSION['user_id']; ?>">
                <div class="form-group">
                  <label for="rating">Rating (1-5):</label>
                  <select name="rating" id="rating" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="recommended">Recommended:</label>
                  <select name="recommended" id="recommended" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="review">Review:</label>
                  <textarea name="review" id="review" rows="4" placeholder="Leave your review below!" required></textarea>
                </div>
                <button type="submit">Submit Review</button>
              </form>
          <?php else: ?>
            <p>Please <a href="login.php">log in</a> to leave a review.</p>
          <?php endif; ?>
        </div>
        </section>
         <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="assets/icons/shelfscape-logo.png" alt="Shelfscape Logo" />
                <div class="social-icons">
                    <a href="#"><img src="assets/icons/X.jfif" alt="X Icon"></a>
                    <a href="#"><img src="assets/icons/facebook.jfif" alt="Facebook Icon"></a>
                    <a href="#"><img src="assets/icons/instagram.jfif" alt="Instagram Icon"></a>
                    <a href="#"><img src="assets/icons/youtube.jfif" alt="YouTube Icon"></a>
                    <a href="#"><img src="assets/icons/linkedin.jfif" alt="LinkedIn Icon"></a>
                </div>
            </div>
            <div class="footer-links">
                <div class="link-column">
                    <h3>Careers</h3>
                    <ul>
                        <li><a href="#">Career Opportunities</a></li>
                        <li><a href="#">Working at ShelfScape</a></li>
                        <li><a href="#">Scholarship</a></li>
                    </ul>
                </div>
                <div class="link-column">
                    <h3>Read with us</h3>
                    <ul>
                        <li><a href="#">Books</a></li>
                        <li><a href="#">eBooks</a></li>
                        <li><a href="#">Magazines</a></li>
                        <li><a href="#">Newspapers</a></li>
                        <li><a href="#">Reading Initiatives</a></li>
                    </ul>
                </div>
                <div class="link-column">
                    <h3>Join Our Mailing List</h3>
                    <ul>
                        <li><a href="contact.php">Subscribe Now</a></li>
                    </ul>
                </div>
                <div class="link-column">
                    <h3>Contact Us!</h3>
                    <ul>
                        <li><a href="contact.php">Contact Now</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>