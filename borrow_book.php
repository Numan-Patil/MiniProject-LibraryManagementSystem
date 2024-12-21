<?php
// Database connection
$serverusername = "localhost";
$userusername = "root";
$password = "";
$dbusername = "library_db";

// Create connection
$conn = new mysqli($serverusername, $userusername, $password, $dbusername);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];

    // Mark the book as borrowed (you can add a `borrowed` column in your database)
    $sql = "UPDATE books SET status = 'borrowed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);

    if ($stmt->execute()) {
        echo "Book successfully borrowed.";
    } else {
        echo "Error updating book status.";
    }

    $stmt->close();
}

$conn->close();
?>
