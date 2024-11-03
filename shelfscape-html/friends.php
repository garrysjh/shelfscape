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
    <title>Shelfscape: Friends</title>
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
    <main class="main-container">
        <h1>Friends</h1>
        
        <!-- Pending Friends Section -->
        <div class="pending-friends">
            <h2>Pending Friends</h2>
            <div class="pending-friends-container">
                <?php if (!empty($pending_friends)): ?>
                    <?php foreach ($pending_friends as $pending_friend): ?>
                        <div class="friend-card">
                            <a href="profile.php?id=<?php echo htmlspecialchars($pending_friend['id']); ?>" class="friend-link">
                                <img src="<?php echo htmlspecialchars($pending_friend['profilePicture']); ?>" alt="Profile Picture" class="profile-pic">
                                <span class="friend-username"><?php echo htmlspecialchars($pending_friend['username']); ?></span>
                            </a>
                            <form method="POST">
                                <input type="hidden" name="friend_id" value="<?php echo htmlspecialchars($pending_friend['id']); ?>">
                                <button type="submit" name="confirm_friend" class="confirm-button">Confirm</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No pending friends.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Confirmed Friends Section -->
        <div class="confirmed-friends">
            <h2>Confirmed Friends</h2>
            <div class="confirmed-friends-container">
                <?php if (!empty($confirmed_friends)): ?>
                    <?php foreach ($confirmed_friends as $confirmed_friend): ?>
                        <div class="friend-card">
                            <a href="profile.php?id=<?php echo htmlspecialchars($confirmed_friend['id']); ?>" class="friend-link">
                                <img src="<?php echo htmlspecialchars($confirmed_friend['profilePicture']); ?>" alt="Profile Picture" class="profile-pic">
                                <span class="friend-username"><?php echo htmlspecialchars($confirmed_friend['username']); ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No confirmed friends.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
