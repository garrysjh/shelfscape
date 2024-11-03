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

$userId = $_SESSION['user_id'];

// Retrieve all book IDs from the cart for the current user
$cart_sql = "SELECT bookId FROM CartItems WHERE userId = ?";
$stmt = $conn->prepare($cart_sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$cart_result = $stmt->get_result();
$bookIds = [];
while ($row = $cart_result->fetch_assoc()) {
    $bookIds[] = $row['bookId'];
}
$stmt->close();

// Remove all items from the cart for the current user
$delete_sql = "DELETE FROM CartItems WHERE userId = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->close();

// Reduce the quantity of each corresponding book in the Books table by one
$update_sql = "UPDATE Books SET quantity = quantity - 1 WHERE bookId = ?";
$stmt = $conn->prepare($update_sql);
foreach ($bookIds as $bookId) {
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
}
$stmt->close();

$conn->close();

// Redirect back to cart.php with success message
echo "<script>
        alert('Books reserved successfully, check your email for further instructions!');
        window.location.href = 'cart.php';
      </script>";
exit();
?>