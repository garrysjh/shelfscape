<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

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

// Get user ID from URL parameter or session
$user_id = $_GET['id'] ?? null;

// Retrieve user data
$sql = "SELECT username, email, phone, profilePicture, timeCreated, lastLogin FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$userData = [];
if ($result->num_rows > 0) {
    // Store data of the user in an array
    $userData = $result->fetch_assoc();
} else {
    $userData = null;
}

// Check if the current user and the profile user are friends
$profile_id = $user_id;
$current_user_id = $_SESSION['user_id'];
$friend_status_sql = "
SELECT 
    *
FROM 
    friends
WHERE 
    ((userId = ? AND friendId = ?) 
    OR (userId = ? AND friendId = ?))
    AND status = 'CONFIRMED'";
$stmt = $conn->prepare($friend_status_sql);
$stmt->bind_param("iiii", $current_user_id, $profile_id, $profile_id, $current_user_id);
$stmt->execute();
$friend_status_result = $stmt->get_result();
$is_friend = $friend_status_result->num_rows > 0;

// Check if a friend request is pending
$pending_status_sql = "
SELECT 
    *
FROM 
    friends
WHERE 
    ((userId = ? AND friendId = ?) 
    OR (userId = ? AND friendId = ?))
    AND status = 'PENDING'";
$stmt = $conn->prepare($pending_status_sql);
$stmt->bind_param("iiii", $current_user_id, $profile_id, $profile_id, $current_user_id);
$stmt->execute();
$pending_status_result = $stmt->get_result();
$is_pending = $pending_status_result->num_rows > 0;

// Retrieve the 3 most recent reviews by the current user
$reviews_sql = "
SELECT 
    b.bookId, b.title, r.review, r.date, b.coverImg, r.rating, r.recommended
FROM 
    reviews r
JOIN 
    books b using(bookId)
WHERE 
    userId = ?
ORDER BY 
    r.date DESC 
LIMIT 3";
$stmt = $conn->prepare($reviews_sql);
$stmt->bind_param("i", $user_id);
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
    <title>Shelfscape</title>
    <meta charset="utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="styles/reset.css" />
    <link rel="stylesheet" href="styles/profile.css"/>
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
    <div class="profile-data">
        <h1>Profile Information</h1>
        <?php if ($userData): ?>
            <img src="<?php echo htmlspecialchars($userData['profilePicture']); ?>" alt="Profile Picture" width="100"><br>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($userData['phone']); ?></p>
            <p><strong>Account Created:</strong> <?php echo htmlspecialchars($userData['timeCreated']); ?></p>
            <p><strong>Last Login:</strong> <?php echo htmlspecialchars($userData['lastLogin']); ?></p>
            <?php if ((int)$_SESSION['user_id'] !== (int)$user_id): ?>
                <?php if ($is_friend): ?>
                    <p class="friends"><strong>Status:</strong> Friends</p>
                <?php elseif ($is_pending): ?>
                    <p class="pending"><strong>Status:</strong> Pending</p>
                <?php else: ?>
                    <form method="POST" action="send_friend_request.php">
                        <input type="hidden" name="friend_id" value="<?php echo htmlspecialchars($profile_id); ?>">
                        <button type="submit">Send Friend Request</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <p>User data not found.</p>
        <?php endif; ?>
        <h1>Recent Reviews</h1>
        <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <div style="float: left; margin-right: 10px;">
                            <img src="<?php echo htmlspecialchars($review['coverImg']); ?>" alt="Book Cover" width="50">
                            <p><strong><?php echo htmlspecialchars($review['title']); ?></strong></p>
                        </div>
                        <div>
                            <p><strong>Review:</strong> <?php echo htmlspecialchars($review['review']); ?></p>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($review['date']); ?></p>
                            <p><strong>Rating:</strong> <?php echo htmlspecialchars($review['rating']); ?> / 5</p>
                            <p><strong>Recommended:</strong> <?php echo $review['recommended'] ? 'Yes' : 'No'; ?></p>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews found.</p>
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
</body>
</html>
