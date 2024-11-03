<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shelfscape</title>
    <meta charset="utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="styles/events.css" />
</head>
<body>
    <!-- Header -->
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

    <!-- Events Section -->
    <main>
        <section class="signature-event">
            <h2>Our Events</h2>
            <div class="signature-event-banner">
                <img src="assets/icons/escape_room.png" alt="Signature Event Image" class="signature-event-image">
                <div class="signature-event-content">
                    <h3>Mystery-Book Escape Room</h3>
                    <p>Sign up now for the Annual Escape Room!</p>
                </div>
            </div>
            <p class="signature-event-description">
                Join us for an unforgettable literary adventure! Solve puzzles and unlock secrets based on your favorite books.
            </p>
            <p class="signature-event-details">
                For more details, view our Instagram at <a href="https://instagram.com" target="_blank">@shelfscape</a>.
            </p>
        </section>

        <!-- Carousel for Events in November -->
        <section class="upcoming-events">
            <h2>Events in NOVEMBER</h2>
            <div class="carousel-wrapper">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="assets/icons/reading_book.png" alt="Story Time">
                        <p class="event-title">Story Time Session</p>
                    </div>
                    <div class="carousel-item">
                        <img src="assets/icons/spelling_bee.png" alt="Spelling Bee">
                        <p class="event-title">Spelling Bee</p>
                    </div>
                    <div class="carousel-item">
                        <img src="assets/icons/AI_event.png" alt="AI Workshop">
                        <p class="event-title">AI Workshop</p>
                    </div>
                </div>
                <button class="carousel-button prev" onclick="prevSlide()">&#10094;</button>
                <button class="carousel-button next" onclick="nextSlide()">&#10095;</button>
            </div>
        </section>
    </main>

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

    <script>        
        let currentIndex = 0;

        function showSlide(index) {
            const slides = document.querySelectorAll('.carousel-item');
            const totalSlides = slides.length;
            currentIndex = (index + totalSlides) % totalSlides;
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

    <style>
        /* Signature Event Styling */
        .signature-event {
            text-align: center;
            margin: 2rem auto;
            max-width: 800px;
        }

        .signature-event-banner {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f3f3;
            padding: 2rem;
            border-radius: 10px;
            margin: 1.5rem auto;
        }

        .signature-event-image {
            width: 60%;
            height: auto;
            border-radius: 8px;
            margin-right: 2rem;
        }

        /* Carousel Styling */
        .carousel-wrapper {
            position: relative;
            overflow: hidden;
            max-width: 800px;
            margin: 2rem auto;
            border-radius: 10px;
            background-color: #f9f9f9;
            padding: 1rem;
        }

        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease;
        }

        .carousel-item {
            min-width: 100%;
            text-align: center;
        }

        .carousel-item img {
            max-width: 80%;
            height: auto;
            margin: 0 auto;
            border-radius: 8px;
        }

        .carousel-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 24px;
            z-index: 1;
        }

        .carousel-button.prev {
            left: 15px;
        }

        .carousel-button.next {
            right: 15px;
        }
    </style>
</body>
</html>
