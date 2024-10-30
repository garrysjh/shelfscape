<?php
include 'book.php'; // Include the file that retrieves the book and reviews
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
    <link rel="stylesheet" href="styles/index.css" />
  </head>
  
<body>
    <!-- Full-width Header -->
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
                <a href="donate.php">Donate</a>
            </div>
            <div class="search-bar">
                <form action="books.php" method="GET">
                    <input type="text" name="query" placeholder="ENTER SERIAL NO OR TITLE" />
                    <button type="submit" class="search-button">Search</button>
                </form>
            </div>
            <div class="account-icon">
                <a href="login.php">
                    <img src="assets/icons/user.png" alt="User Icon" />
                </a>
            </div>
        </nav>
    </header>

    <!-- Full-width Carousel -->
    <section class="we-create">
        <br>
        <div class="carousel-wrapper">
            <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="assets/icons/library_carousel_1.png" alt="Image 1 Description">
                </div>
                <div class="carousel-item">
                  <img src="assets/icons/library_carousel_2.png" alt="Image 2 Description">
                </div>
                <div class="carousel-item">
                  <img src="assets/icons/library_carousel_3.png" alt="Image 3 Description">
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
        <img src="<?php echo $book['coverImg']; ?>" alt="<?php echo $book['title']; ?> Cover Image" class="book-cover">
        <h3><?php echo $book['title']; ?></h3>
        <p><strong>Author: </strong><?php echo $book['author']; ?></p>
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
                        <img src="<?php echo $review['profilePicture']; ?>" alt="<?php echo $review['username']; ?>" class="reviewer-img">
                        <div>
                            <p class="reviewer-name"><?php echo htmlspecialchars($review['username']); ?></p>
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
                        <li><a href="contact.html">Subscribe Now</a></li>
                    </ul>
                </div>
                <div class="link-column">
                  <h3>Contact Us!</h3>
                  <ul>
                      <li><a href="contact.html">Contact Now</a></li>
                  </ul>
              </div>
            </div>
        </div>
    </footer>

    <script>
        let currentIndex = 0;

        function showSlide(index) {
            const slides = document.querySelectorAll('.carousel-item');
            const totalSlides = slides.length;

            if (index >= totalSlides) {
                currentIndex = 0;
            } else if (index < 0) {
                currentIndex = totalSlides - 1;
            } else {
                currentIndex = index;
            }

            document.querySelector('.carousel-inner').style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        function nextSlide() {
            showSlide(currentIndex + 1);
        }

        function prevSlide() {
            showSlide(currentIndex - 1);
        }

        showSlide(currentIndex);
    </script>
</body>
</html>
