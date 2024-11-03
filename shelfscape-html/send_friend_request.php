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

// Get current user ID and friend ID from POST request
$current_user_id = $_SESSION['user_id'];
$friend_id = $_POST['friend_id'];

// Insert friend request into database
$insert_sql = "INSERT INTO friends (userId, friendId, status) VALUES (?, ?, 'PENDING')";
$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("ii", $current_user_id, $friend_id);
$stmt->execute();
$stmt->close();

$conn->close();

// Redirect back to profile page
header("Location: profile.php?Id=" . $friend_id);
exit();
?>