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
    CASE 
        WHEN userId = ? THEN friendId 
        ELSE userId 
    END AS friend
FROM 
    Friends
WHERE 
    (userId = ? OR friendId = ?)
    AND status = 'CONFIRMED'";
$stmt = $conn->prepare($confirmed_sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$confirmed_result = $stmt->get_result();
$confirmed_friends = [];
while ($row = $confirmed_result->fetch_assoc()) {
    $confirmed_friends[] = $row['friend'];
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
                <a href="events.php">Events</a>
                <a href="aboutus.php">About</a>
                <a href="donate.php">Donate</a>
            </div>
            <div class="search-bar">
                <form action="books.php" method="GET">
                    <input type="text" name="query" placeholder="ENTER SERIAL NO OR TITLE" />
                    <button type="submit" class="search-button">Search</button>
                    <i class="fas fa-search"></i>
                </form>
            </div>
            <div class="account-icon">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                    <div class="dropdown">
                        <img src="<?php echo $_SESSION['profilePicture']; ?>" alt="User Icon" class="usericon"/>
                        <div class="dropdown-content">
                            <a href="profile.php">Profile</a>
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
        <div class="confirmed-friends">
            <h2>Confirmed Friends</h2>
            <?php if (!empty($confirmed_friends)): ?>
                <ul>
                    <?php foreach ($confirmed_friends as $confirmed_friend): ?>
                        <li><?php echo htmlspecialchars($confirmed_friend); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No confirmed friends.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>