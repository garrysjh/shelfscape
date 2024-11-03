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

// Handle friend confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_friend'])) {
    $friend_id = $_POST['friend_id'];
    $update_sql = "UPDATE Friends SET status = 'CONFIRMED' WHERE userId = ? AND friendId = ? AND status = 'PENDING'";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $friend_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Retrieve pending friends
$pending_sql = "SELECT u.id, u.username, u.profilePicture FROM Friends f LEFT JOIN user u ON f.userId = u.id WHERE status = 'PENDING' AND friendId = ?";
$stmt = $conn->prepare($pending_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pending_result = $stmt->get_result();
$pending_friends = [];
while ($row = $pending_result->fetch_assoc()) {
    $pending_friends[] = [
        'id' => $row['id'],
        'username' => $row['username'],
        'profilePicture' => $row['profilePicture']
    ];
}
$stmt->close();

// Retrieve confirmed friends
$confirmed_sql = "
SELECT 
    u.id AS friendId,
    u.username,
    u.profilePicture
FROM 
    Friends f
JOIN 
    User u 
    ON u.id = CASE 
                 WHEN f.userId = ? THEN f.friendId 
                 ELSE f.userId 
              END
WHERE 
    (f.userId = ? OR f.friendId = ?)
    AND f.status = 'CONFIRMED';";
$stmt = $conn->prepare($confirmed_sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$confirmed_result = $stmt->get_result();
$confirmed_friends = [];
while ($row = $confirmed_result->fetch_assoc()) {
    $confirmed_friends[] = [
        'id' => $row['friendId'],
        'username' => $row['username'],
        'profilePicture' => $row['profilePicture']
    ];
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Friends</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="styles/reset.css" />
    <link rel="stylesheet" href="styles/friends.css"/>
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
    <main class="main-container">
        <h1>Friends</h1>
        <div class="pending-friends">
            <h2>Pending Friends</h2>
            <div class="pending-friends-container">
            <?php if (!empty($pending_friends)): ?>

                    <?php foreach ($pending_friends as $pending_friend): ?>
                        <div class="pending-friend">    
                            <a href="profile.php?id=<?php echo htmlspecialchars($pending_friend['id']); ?>" class="friend-link">
                                <img src="<?php echo htmlspecialchars($pending_friend['profilePicture']); ?>" alt="Profile Picture" class="profile-pic">
                                <span class="friend-username"><?php echo htmlspecialchars($pending_friend['username']); ?></span>
                            </a>
                            
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="friend_id" value="<?php echo htmlspecialchars($pending_friend['id']); ?>">
                                <button type="submit" name="confirm_friend">Confirm</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                    </div>
            <?php else: ?>
                <p>No pending friends.</p>
            <?php endif; ?>
        </div>

            <h2>Confirmed Friends</h2>
            <div class="pending-friends-container">
            <?php if (!empty($confirmed_friends)): ?>
                    <?php foreach ($confirmed_friends as $confirmed_friend): ?>
                        <div class="pending-friends">
                        <a href="profile.php?id=<?php echo htmlspecialchars($confirmed_friend['id']); ?>" class="friend-link">
                            <img src="<?php echo htmlspecialchars($confirmed_friend['profilePicture']); ?>" alt="Profile Picture" class="profile-pic">
                            <span class="friend-username"><?php echo htmlspecialchars($confirmed_friend['username']); ?></span>
                        </a>
                        </div>
                    <?php endforeach; ?>
                    </div>
            <?php else: ?>
                <p>No confirmed friends.</p>
            <?php endif; ?>
        </div>
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
</body>
</html>
