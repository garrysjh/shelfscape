<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

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

// Get user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, phone, profilePicture FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $phone, $profilePicture);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];
    $new_password = $_POST['password'];
    $new_profilePicture = $_FILES['profilePicture']['name'];

    // Handle profile picture upload
    if ($new_profilePicture) {
        $target_dir = "assets/";
        $target_file = $target_dir . basename($_FILES["profilePicture"]["name"]);
        move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file);
    } else {
        $target_file = $profilePicture;
    }

    // Update user details
    if ($new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE user SET username = ?, email = ?, phone = ?, password = ?, profilePicture = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $new_username, $new_email, $new_phone, $hashed_password, $target_file, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE user SET username = ?, email = ?, phone = ?, profilePicture = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $new_username, $new_email, $new_phone, $target_file, $user_id);
    }
    $stmt->execute();
    $stmt->close();

    // Update session variables
    $_SESSION['username'] = $new_username;
    $_SESSION['email'] = $new_email;
    $_SESSION['phone'] = $new_phone;
    $_SESSION['profilePicture'] = $target_file;

    echo "<script>
            alert('Settings updated successfully!');
            window.location.href = 'settings.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
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
    <link rel="stylesheet" href="styles/reset.css" />
    <link rel="stylesheet" href="styles/settings.css"/>
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
                <a href="events.html">Events</a>
                <a href="aboutus.html">About</a>
                <a href="donate.html">Donate</a>
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
    <div class="settings-div">
        <h1>Change your profile information</h1>
        <form action="settings.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password (leave blank to keep current password):</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="profilePicture">Profile Picture:</label>
                <input type="file" id="profilePicture" name="profilePicture">
                <?php if ($profilePicture): ?>
                    <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" width="100">
                <?php endif; ?>
            </div>
            <button type="submit">Update Settings</button>
        </form>
    </div>
</body>
</html>