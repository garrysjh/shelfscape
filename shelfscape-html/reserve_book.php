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

// Get form data
$bookId = $_POST['bookId'];
$userId = $_SESSION['user_id'];

// Check if the book is already reserved for its quantity
$check_sql = "SELECT COUNT(c.id), b.quantity
FROM CartItems c 
JOIN books b ON c.bookId = b.bookId 
WHERE c.bookId = ? 
HAVING COUNT(c.id) < b.quantity;";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("s", $bookId);
$stmt->execute();
$check_result = $stmt->get_result();

if ($check_result->num_rows > 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'duplicate_entry']);
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();


// Insert reservation into database
$insert_sql = "INSERT INTO cartitems (bookId, userId) VALUES (?, ?)";
$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("si", $bookId, $userId);

try {
    if ($stmt->execute()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'redirect' => 'cart.php']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'insert_failed']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'fatal_error', 'message' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>