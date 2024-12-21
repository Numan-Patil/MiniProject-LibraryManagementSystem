<?php
// Database connection
$servername = "localhost";
$username = "root";  // Use your database username
$password = "";      // Use your database password
$dbname = "library_db"; // Use your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the data from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$enteredKey = $data['securityKey'];

// Prepare SQL to fetch the security key from the admin table
$sql = "SELECT security_key FROM admin WHERE id = 1"; // Assuming you have one admin record
$result = $conn->query($sql);

$response = ['status' => 'error'];

if ($result->num_rows > 0) {
    // Assuming there's only one row for the admin
    $row = $result->fetch_assoc();
    
    // Check if the entered security key matches the stored key
    if ($enteredKey === $row['security_key']) {
        $response = ['status' => 'success'];
    }
}

$conn->close();

// Send the response back as JSON
echo json_encode($response);
?>
