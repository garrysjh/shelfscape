<?php
session_start();

// Database connection
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
$bookId = isset($_GET['bookId']) ? $_GET['bookId'] : 0;

// Get filter parameters
$ratingFilter = isset($_GET['rating']) ? $_GET['rating'] : '';
$recommendedFilter = isset($_GET['recommended']) ? $_GET['recommended'] : '';

// Build the SQL query with filters
$reviews_sql = "
SELECT u.username, u.profilePicture, r.userId, r.review, r.rating, r.date, r.recommended
FROM reviews r 
JOIN user u ON r.userId = u.id 
WHERE r.bookId = ?";

if ($ratingFilter !== '') {
    $reviews_sql .= " AND r.rating = ?";
}
if ($recommendedFilter !== '') {
    $reviews_sql .= " AND r.recommended = ?";
}

$reviews_sql .= " ORDER BY r.date DESC";

$stmt = $conn->prepare($reviews_sql);

if ($ratingFilter !== '' && $recommendedFilter !== '') {
    $stmt->bind_param("iii", $bookId, $ratingFilter, $recommendedFilter);
} elseif ($ratingFilter !== '') {
    $stmt->bind_param("ii", $bookId, $ratingFilter);
} elseif ($recommendedFilter !== '') {
    $stmt->bind_param("ii", $bookId, $recommendedFilter);
} else {
    $stmt->bind_param("i", $bookId);
}

$stmt->execute();
$reviews_result = $stmt->get_result();
$reviews = [];
while ($row = $reviews_result->fetch_assoc()) {
    $reviews[] = $row;
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shelfscape: All Reviews</title>
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="styles/reviews.css">
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
        <div class="filter">
        <a href="book.php?id=<?php echo $bookId; ?>">Back to Book</a>
        <h1>All Reviews</h1>
        <form method="GET" action="all_reviews.php">
            <input type="hidden" name="bookId" value="<?php echo htmlspecialchars($bookId); ?>">
            <label for="rating">Rating:</label>
            <select name="rating" id="rating">
                <option value="">All</option>
                <option value="1" <?php echo $ratingFilter === '1' ? 'selected' : ''; ?>>1</option>
                <option value="2" <?php echo $ratingFilter === '2' ? 'selected' : ''; ?>>2</option>
                <option value="3" <?php echo $ratingFilter === '3' ? 'selected' : ''; ?>>3</option>
                <option value="4" <?php echo $ratingFilter === '4' ? 'selected' : ''; ?>>4</option>
                <option value="5" <?php echo $ratingFilter === '5' ? 'selected' : ''; ?>>5</option>
            </select>
            <label for="recommended">Recommended:</label>
            <select name="recommended" id="recommended">
                <option value="">All</option>
                <option value="1" <?php echo $recommendedFilter === '1' ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo $recommendedFilter === '0' ? 'selected' : ''; ?>>No</option>
            </select>
            <button type="submit">Filter</button>
        </form>
                </div>
        <div class="all-reviews">
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
                        <p class="review-recommended"><strong>Recommended: </strong><?php echo $review['recommended'] ? 'Yes' : 'No'; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="review-content">No reviews found.</p>
            <?php endif; ?>
        </div>

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
    </main>
</body>
</html>