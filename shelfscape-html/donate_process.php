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
$title = $_POST['title'];
$author = $_POST['author'];
$description = $_POST['description'];
$language = $_POST['language'];
$isbn = $_POST['isbn'];
$genres = $_POST['genres'];
$genres_array = explode(',', $genres);
$genres_json = json_encode($genres_array);
$genres = $genres_json;
$coverImg = "https://images.isbndb.com/covers/28/52/".$isbn.".jpg";

// Insert book into database
$insert_sql = "INSERT INTO Books (title, author, description, language, isbn, genres, coverImg) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("sssssss", $title, $author, $description, $language, $isbn, $genres, $coverImg);

if ($stmt->execute()) {
    // Redirect back to donate.php with success message
    echo "<script>
            alert('Book donated successfully!');
            window.location.href = 'donate.php';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>