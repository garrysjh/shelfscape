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

$user_id = $_SESSION['user_id'];

// Retrieve confirmed friends
$posts_sql = "
SELECT u.username, u.profilePicture, r.userId, r.review, b.title, b.coverImg, r.rating, r.date, r.recommended from reviews r LEFT JOIN books b using(bookId)  
LEFT JOIN user u on r.userId = u.id
where r.userId in
(SELECT 
    u.id
FROM 
    Friends f
JOIN 
    user u 
    ON u.id = CASE 
                 WHEN f.userId = ? THEN f.friendId 
                 ELSE f.userId 
               END
WHERE 
    (f.userId = ? OR f.friendId = ?)
    AND f.status = 'CONFIRMED')
ORDER BY 
r.date DESC;";
$stmt = $conn->prepare($posts_sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$confirmed_result = $stmt->get_result();
$posts = [];
while ($row = $confirmed_result->fetch_assoc()) {
    $posts[] = [
        'username' => $row['username'],
        'profilePicture' => $row['profilePicture'],
        'userId' => $row['userId'],
        'review' => $row['review'],
        'title' => $row['title'],
        'coverImg' => $row['coverImg'],
        'rating' => $row['rating'],
        'date' => $row['date'],
        'recommended' => $row['recommended']
    ];
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed</title>
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="styles/feed.css">
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
    <div class="feed-container">
    <h1>Friend Activity</h1>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="post-header">
                    <img src="<?php echo htmlspecialchars($post['profilePicture']); ?>" alt="Profile Picture" class="profile-pic">
                    <div class="user-info">
                        <p class="username"><?php echo htmlspecialchars($post['username']); ?></p>
                        <p class="date"><?php echo htmlspecialchars($post['date']); ?></p>
                    </div>
                </div>
                <div class="post-content">
                    <img src="<?php echo htmlspecialchars($post['coverImg']); ?>" alt="Book Cover" class="book-cover">
                    <div class="review-info">
                        <h3 class="book-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="review"><?php echo htmlspecialchars($post['review']); ?></p>
                        <p class="rating">Rating: <?php echo htmlspecialchars($post['rating']); ?>/5</p>
                        <p class="recommended"><?php echo $post['recommended'] ? 'Recommended' : 'Not Recommended'; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
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
</body>
</html>
