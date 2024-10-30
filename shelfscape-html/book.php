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

// Define the default book ID for "Book of the Month"
$defaultBookId = "10210.Jane_Eyre"; // Change this to your desired book ID
$bookId = isset($_GET['id']) ? $_GET['id'] : $defaultBookId;

// Retrieve book details
$sql = "SELECT title, author, coverImg, isbn, description, genres FROM Books WHERE bookId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

// Retrieve 3 most recent reviews for the book
$sql = "SELECT u.username, u.profilePicture, r.rating, r.review, r.date, r.recommended FROM reviews r LEFT JOIN user u ON r.userId = u.id WHERE bookId = ? ORDER BY r.date DESC LIMIT 3";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

