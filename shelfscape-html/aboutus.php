<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shelfscape</title>
    <meta charset="utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="styles/aboutus.css" />
    <style>
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
            width: 300%; /* Adjust width based on the number of slides */
        }

        .carousel-item {
            min-width: 100%; /* Each item takes up the full width of the container */
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
        <!-- About Us Section -->
        <section class="we-are">
            <h2>About Us</h2>
            <p class="intro-text">Hello! ShelfScape was founded by Garry and Josiah, a pair year 4 students from NTU EEE. </p>
            <br>
            <div class="about-content">
                <img src="assets/icons/shelfscape_img.png" alt="We Are ShelfScape Image" class="we-are-image">
                <div class="text">
                    <br>
                    <h3>We Are ShelfScape</h3>
                    <p>Shelfscape is on a mission to disrupt the conventional library system. We’re the first online digital library offering free e-books to everyone, anywhere. With a collection of over 10 million books, there’s something for everyone. Join us and explore ShelfScape today!</p>
                </div>
            </div>
        </section>

        <!-- Full-width Carousel -->
        <section class="we-create">
            <br>
            <h2>Feel free to see where it all started!</h2>
            <br>
            <div class="carousel-wrapper">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="assets/icons/location_1.png" alt="Library Scene 1">
                    </div>
                    <div class="carousel-item">
                        <img src="assets/icons/location_2.png" alt="Library Scene 2">
                    </div>
                    <div class="carousel-item">
                        <img src="assets/icons/location_3.png" alt="Library Scene 3">
                    </div>
                </div>
                <button class="carousel-button prev" onclick="prevSlide()">&#10094;</button>
                <button class="carousel-button next" onclick="nextSlide()">&#10095;</button>
            </div>
        </section>

        <!-- Locate Us Section -->
        <section class="locate-us">
            <h2>Locate Us</h2>
            <p>School of Electrical and Electronic Engineering</p>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.72281751501!2d103.67816467586454!3d1.3426961986446004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da0f75a53bec9d%3A0xd08547c621f6dca6!2sSchool%20of%20Electrical%20and%20Electronic%20Engineering%20(EEE)!5e0!3m2!1sen!2ssg!4v1730082262426!5m2!1sen!2ssg" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
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
</body>
</html>
