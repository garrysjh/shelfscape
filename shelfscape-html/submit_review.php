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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $bookId = $_POST['bookId'];
    $userId = $_POST['userId'];
    $rating = $_POST['rating'];
    $recommended = $_POST['recommended'];
    $review = $_POST['review'];

    // Insert review into database
    $stmt = $conn->prepare("INSERT INTO reviews (bookId, userId, rating, recommended, review) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siiss", $bookId, $userId, $rating, $recommended, $review);

    if ($stmt->execute()) {
        // Redirect to book details page with success message
        $_SESSION['message'] = "Review submitted successfully!";
        header("Location: book.php?id=" . $bookId);
    } else {
        // Redirect to book details page with error message
        $_SESSION['error'] = "Failed to submit review. Please try again.";
        header("Location: book.php?id=" . $bookId);
    }

    $stmt->close();
    header("Location: book.php?id=" . $bookId);
    exit();
}

$conn->close();
?>