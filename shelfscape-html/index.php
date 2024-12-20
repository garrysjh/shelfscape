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

// Retrieve bookId with the highest average rating for the current month and year
$sql = "SELECT bookId
        FROM Reviews
        WHERE MONTH(date) = MONTH(CURRENT_DATE())
          AND YEAR(date) = YEAR(CURRENT_DATE())
        GROUP BY bookId
        ORDER BY AVG(rating) DESC
        LIMIT 1";
$result = $conn->query($sql);

$bookId = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $bookId = $row['bookId'];
}

// Retrieve book details
$bookDetails = [];
if ($bookId) {
    $stmt = $conn->prepare("SELECT * FROM Books WHERE bookId = ?");
    $stmt->bind_param("s", $bookId);
    $stmt->execute();
    $bookDetails = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Retrieve 3 most recent reviews for the book
$reviews = [];
if ($bookId) {
    $stmt = $conn->prepare("SELECT u.id, u.username, u.profilePicture, r.rating, r.review, r.date, r.recommended FROM reviews r LEFT JOIN user u on r.userId = u.id WHERE bookId = ? ORDER BY r.date DESC LIMIT 3");
    $stmt->bind_param("s", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Shelfscape: Homepage</title>
    <meta charset="utf-8" />
    <link rel="icon" type="image/x-icon" href="./assets/icons/shelfscape-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles/reset.css" />
    <link rel="stylesheet" href="styles/index.css" />
  </head>
  
<body>
    <!-- Full-width Header -->
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
                            <a href="cart.php">Cart</a>
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

    <!-- Welcome Banner -->
<section class="welcome-banner">
    <h1>Welcome to ShelfScape!</h1>
    <p>Your digital library, anywhere, anytime!</p>
</section>

<!-- Full-width Carousel -->
<section class="we-create">
        <div class="carousel-wrapper">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/icons/banner_1.png" alt="Image 1 Description">
                </div>
                <div class="carousel-item">
                    <img src="assets/icons/banner_2.png" alt="Image 2 Description">
                </div>
                <div class="carousel-item">
                    <img src="assets/icons/banner_3.png" alt="Image 3 Description">
                </div>
            </div>
            <button class="carousel-button prev" onclick="prevSlide()">&#10094;</button>
            <button class="carousel-button next" onclick="nextSlide()">&#10095;</button>
        </div>
    </section>

    <section class="book-review-container">
    <!-- Book Details -->
    <div class="featured-book">
        <h2>Book of the Month</h2>
        <br>
        <a href="book.php?id=<?php echo $bookDetails['bookId']; ?>">
            <img src="<?php echo $bookDetails['coverImg']; ?>" alt="<?php echo $bookDetails['title']; ?> Cover Image" class="book-cover">
        </a>
        <h3><a href="book.php?id=<?php echo $bookDetails['bookId']; ?>"><?php echo $bookDetails['title']; ?></a></h3>
        <p><strong>Author: </strong><?php echo $bookDetails['author']; ?></p>
    </div>
    <!-- Reviews Section -->
    <div class="latest-reviews">
        <h2>Recent Reviews</h2>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <h4>Review by <?php echo htmlspecialchars($review['username']); ?></h4>
                    <p><?php echo htmlspecialchars($review['review']); ?></p>
                    <div class="review-meta">
                    <a href="profile.php?id=<?php echo htmlspecialchars($review['id']); ?>">
                        <img src="<?php echo $review['profilePicture']; ?>" alt="<?php echo $review['username']; ?>" class="reviewer-img">
                        </a>
                        <div>
                            <p class="reviewer-name">
                                <a href="profile.php?id=<?php echo htmlspecialchars($review['id']); ?>">
                                    <?php echo htmlspecialchars($review['username']); ?>
                                </a>
                            </p>
                            <p class="review-date"><?php echo htmlspecialchars($review['date']); ?></p>
                            <p class="review-rating">Rating: <?php echo htmlspecialchars($review['rating']); ?>/5</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-reviews">No reviews found.</p>
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

    <!-- Full-width Footer -->
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

     <!-- JavaScript for Auto-slide and Hover Effects -->
     <script>
        let currentIndex = 0;
        const slides = document.querySelectorAll('.carousel-item');
        const totalSlides = slides.length;

        // Function to show a specific slide
        function showSlide(index) {
            currentIndex = (index + totalSlides) % totalSlides;
            document.querySelector('.carousel-inner').style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        // Next and Previous slide functions
        function nextSlide() { showSlide(currentIndex + 1); }
        function prevSlide() { showSlide(currentIndex - 1); }

        // Auto-slide functionality
        let autoSlideInterval = setInterval(nextSlide, 3000);

        // Pause auto-slide on hover
        document.querySelector('.carousel-wrapper').addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
        document.querySelector('.carousel-wrapper').addEventListener('mouseleave', () => {
            autoSlideInterval = setInterval(nextSlide, 5000);
        });

        showSlide(currentIndex);
    </script>

    
    </style>
</body>
</html>
