<?php
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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO user (username, phone, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $phone, $email, $hashed_password);

    try {
        // Execute the statement
        if ($stmt->execute()) {
            // Show alert and redirect to login page upon successful registration
            echo "<script>
                    alert('Registration successful!');
                    window.location.href = 'login.php';
                  </script>";
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Check for duplicate entry error
        if ($e->getCode() == 1062) {
            echo "<script>
                    alert('Your account credentials already exist, use another username/email/phone!');
                    window.location.href = 'register.php';
                  </script>";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
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
    <link rel="stylesheet" href="styles/reset.css" />
    <link rel="stylesheet" href="styles/register.css"/>
  </head>
  <body>
    <header>
      <nav class="navbar">
        <div class="logo">
            <a href="index.html">
          <img src="assets/icons/shelfscape-logo.png" alt="Shelfscape Logo" />
        </a>
        </div>
        <div class="nav-links">
          <a href="#">Books</a>
          <div class="dropdown">
            <a href="#">Categories</a>
            <div class="dropdown-content">
              <a href="#">Fantasy</a>
              <a href="#">Product 2</a>
              <a href="#">Product 3</a>
            </div>
          </div>
          <a href="#">About</a>
          <a href="#">Services</a>
          <a href="#">Contact</a>
        </div>
        <div class="search-bar">
          <input type="text" placeholder="ENTER SERIAL NO OR TITLE" />
          <button class="search-button">Search</button>
          <i class="fas fa-search"></i>
        </div>
        <div class="account-icon">
          <a href="login.html">
            <img src="assets/icons/user.png" alt="User Icon" />
          </a>
        </div>
      </nav>
    </header>
    <div class="main-content">
        <form action="register.php" method="post">
          <div>
            <label for="username">Username:</label>
          </div>
          <div>
            <input type="text" id="username" name="username" required placeholder="Input your username">
          </div>
          <div>
            <label for="email">Email:</label>
          </div>
          <div>
            <input type="text" id="email" name="email" required placeholder="Input your email">
          </div>
          <div>
            <label for="phone">Mobile Phone:</label>
          </div>
          <div>
            <input type="tel" id="phone" name="phone" required placeholder="Input your phone number">
          </div>
          <div>
            <label for="password">Password:</label>
          </div>
          <div>
            <input type="password" id="password" name="password" required placeholder="Input your password">
          </div>
          <div>
            <label for="confirmPassword">Confirm Password:</label>
          </div>
          <div>
            <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="Confirm your password">
          </div>
          <div class="button-container">
            <button type="submit" class="register-button">Register</button>
          </div>
        </form>
        <u>
            <a href="login.html">
            Already have an account? Log In here
        </a>
        </u>
      </div>
  </body>
  <script></script>
</html>