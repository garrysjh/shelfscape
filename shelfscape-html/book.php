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
$bookId = isset($_GET['id']) ? $_GET['id'] : 0;

// Retrieve book details
$book_sql = "SELECT * FROM Books WHERE BookId = ?";
$stmt = $conn->prepare($book_sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$book_result = $stmt->get_result();
$book = $book_result->fetch_assoc();
$stmt->close();

// Retrieve recent reviews for the book
$reviews_sql = "
SELECT u.username, u.profilePicture, r.userId, r.review, r.rating, r.date 
FROM reviews r 
JOIN user u ON r.userId = u.id 
WHERE r.bookId = ? 
ORDER BY r.date DESC 
LIMIT 3";
$stmt = $conn->prepare($reviews_sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$reviews_result = $stmt->get_result();
$reviews = [];
$total_rating = 0;
$review_count = 0;
while ($row = $reviews_result->fetch_assoc()) {
    $reviews[] = $row;
    $total_rating += $row['rating'];
    $review_count++;
}
$stmt->close();

// Calculate average rating for the current book
$average_rating = $review_count > 0 ? $total_rating / $review_count : 0;

// Retrieve average rating for all books
$all_books_sql = "
SELECT AVG(rating) AS avg_rating 
FROM reviews";
$stmt = $conn->prepare($all_books_sql);
$stmt->execute();
$all_books_result = $stmt->get_result();
$all_books_avg_rating = $all_books_result->fetch_assoc()['avg_rating'];
$stmt->close();

// Retrieve ranking of the current book
$ranking_sql = "
SELECT COUNT(*) + 1 AS rank 
FROM (
    SELECT AVG(rating) AS avg_rating 
    FROM reviews 
    GROUP BY bookId 
    HAVING avg_rating > ?
) AS subquery";
$stmt = $conn->prepare($ranking_sql);
$stmt->bind_param("d", $average_rating);
$stmt->execute();
$ranking_result = $stmt->get_result();
$ranking = $ranking_result->fetch_assoc()['rank'];
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
        <p class="average"><strong>Average Rating: </strong><?php echo $average_rating > 0 ? number_format($average_rating, 2) . '/5' : 'No rating'; ?></p>
        <p class="ranking"><strong>Ranking: </strong><?php echo $ranking; ?> out of all books</p>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <div class="review-title">
                      <div class="review-profile">
                        <a href="profile.php?id=<?php echo $review['userId']; ?>">
                      <img src="<?php echo $review['profilePicture']; ?>" alt="User Icon" class="otherusericon"/>
                      </a>
                      <a href="profile.php?id=<?php echo $review['userId']; ?>">
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
            <p class="review-content">No reviews found.</p>
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