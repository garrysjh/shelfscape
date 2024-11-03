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

// Get reservation ID from POST request
$userId = $_POST['userId'];
$bookId = $_POST['bookId'];

// Delete reservation from database
$delete_sql = "DELETE FROM CartItems WHERE userId = ? AND bookId = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("is", $userId, $bookId);

if ($stmt->execute()) {
    // Redirect back to cart.php with success message
    echo "<script>
            alert('Item removed from cart successfully!');
            window.location.href = 'cart.php';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>