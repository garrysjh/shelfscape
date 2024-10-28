<?php
session_start();

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
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, username, password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Store user details in session
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Redirect to dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password
            echo "<script>
                    alert('Invalid username or password!');
                    window.location.href = 'login.php';
                  </script>";
        }
    } else {
        // User does not exist
        echo "<script>
                alert('Invalid username or password!');
                window.location.href = 'login.php';
              </script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>