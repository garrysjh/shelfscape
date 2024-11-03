<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shelfscape: About Us</title>
    <meta charset="utf-8" />
    <link rel="icon" type="image/x-icon" href="./assets/icons/shelfscape-logo.png">
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
    <body>
    <section class="about-us-section">
    <h2>About Us</h2>

    <!-- Who We Are -->
    <div class="who-we-are">
        <h3>Who We Are</h3>
        <div class="content">
            <img src="assets/icons/josiah_garry.png" display="block" margin="0 auto" height="650px" width="940px" alt="Josiah and Garry" class="profile-img">
            <p>Hello! We are Josiah and Garry, final-year students at Nanyang Technological University (NTU), pursuing our degrees in Electrical and Electronic Engineering (EEE). As passionate technologists and avid readers, we've combined our skills and interests to create ShelfScape.</p>
        </div>
    </div>

    <!-- Our Story -->
    <div class="our-story">
        <h3>Our Story</h3>
        <div class="content">
            <img src="assets/icons/ntu_campus.png" display="block" margin="0 auto" height="650px" width="940px" alt="NTU Campus" class="story-img">
            <p>Our journey began on the bustling campus of NTU. We met during a freshman engineering orientation and quickly bonded over a shared love for technology and literature. Between lectures and lab sessions, we'd often discuss the latest tech trends and our favorite books.</p>
            <p>One evening, while studying in the campus library, we noticed that many students struggled to find the resources they needed. Textbooks were expensive, library copies were limited, and not everyone could afford e-books. This sparked an idea: What if we could make books accessible to everyone, anytime, anywhere?</p>
        </div>
    </div>

    <!-- The Birth of ShelfScape -->
    <div class="birth-of-shelfscape interactive-card">
        <h3>The Birth of ShelfScape</h3>
        <div class="content">
            <p>Fueled by this vision, we embarked on a mission to disrupt the conventional library system. We wanted to leverage our engineering skills to create a platform that democratizes access to knowledge. After countless brainstorming sessions and late-night coding marathons, ShelfScape was born—the first online digital library offering free e-books to everyone.</p>
            <img src="assets/icons/coding_sessions.png" display="block" margin="0 auto" height="650px" width="940px" alt="Late-night coding session" class="interactive-img">
        </div>
    </div>

    <!-- Our Mission -->
    <div class="our-mission">
        <h3>Our Mission</h3>
        <div class="content">
            <div class="mission-item">
                <br>
                <p><strong>Accessibility:</strong> Provide unrestricted access to a vast collection of books across all genres.</p>
            </div>
            <div class="mission-item">
                <br>
                <p><strong>Community:</strong> Build a global community of readers and learners.</p>
            </div>
            <div class="mission-item">
                <br>
                <p><strong>Innovation:</strong> Continually enhance our platform using the latest technologies.</p>
            </div>
        </div>
    </div>

    <!-- Our Vision for the Future -->
    <div class="our-vision">
        <h3>Our Vision for the Future</h3>
        <ul>
            <li>Expand Our Library: Partner with more authors and publishers to enrich our collection.</li>
            <li>Enhance User Experience: Integrate AI recommendations, personalized reading lists, and interactive community features.</li>
            <li>Promote Education: Collaborate with educational institutions to support learning and literacy programs worldwide.</li>
        </ul>
    </div>

    <div class="join-us">
        <h3>Join Us on This Journey</h3>
        <p>ShelfScape is more than just a library; it's a movement toward accessible education and shared knowledge. Whether you're a casual reader, a student, or someone with a thirst for knowledge, we invite you to explore what ShelfScape has to offer.</p>
    </div>
</section>




    <section class="we-create">
    <h2 class="carousel-section-title">Where it all started!</h2>
    <div class="carousel-wrapper">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/icons/location_2.png" alt="Library Scene 1">
            </div>
            <div class="carousel-item">
                <img src="assets/icons/location_3.png" alt="Library Scene 2">
            </div>
            <div class="carousel-item">
                <img src="assets/icons/location_1.png" alt="Library Scene 3">
            </div>
        </div>
        <button class="carousel-button prev" onclick="prevSlide()">&#10094;</button>
        <button class="carousel-button next" onclick="nextSlide()">&#10095;</button>
    </div>
</section>


    <!-- Locate Us Section -->
    <section class="locate-us section-wrapper">
        <h2>Locate Us</h2>
        <p>School of Electrical and Electronic Engineering</p>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.72281751501!2d103.67816467586454!3d1.3426961986446004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da0f75a53bec9d%3A0xd08547c621f6dca6!2sSchool%20of%20Electrical%20and%20Electronic%20Engineering%20(EEE)!5e0!3m2!1sen!2ssg!4v1730082262426!5m2!1sen!2ssg" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>
</body>

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

// Function to display the slide at the specified index
function showSlide(index) {
    const slides = document.querySelectorAll('.carousel-item');
    const totalSlides = slides.length;

    // Wrap around the currentIndex within bounds
    currentIndex = (index + totalSlides) % totalSlides;

    // Adjust the carousel-inner position to display the selected slide
    document.querySelector('.carousel-inner').style.transform = `translateX(-${currentIndex * 100}%)`;
}

// Move to the next slide
function nextSlide() {
    showSlide(currentIndex + 1);
}

// Move to the previous slide
function prevSlide() {
    showSlide(currentIndex - 1);
}

// Set up automatic sliding with a 5-second interval
let autoSlideInterval = setInterval(nextSlide, 5000);

// Pause auto-slide on mouse hover, resume on mouse leave
const carouselWrapper = document.querySelector('.carousel-wrapper');
carouselWrapper.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
carouselWrapper.addEventListener('mouseleave', () => {
    autoSlideInterval = setInterval(nextSlide, 5000);
});

// Initialize the carousel display
showSlide(currentIndex);


    </script>
</body>
</html>
