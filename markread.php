<?php
$host = 'localhost';
$db = 'library_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "UPDATE notifications SET status = 'read' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo "Notification marked as read.";
    } else {
        echo "Failed to mark notification as read.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
