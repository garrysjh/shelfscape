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