<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variable to store error message
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $usn = trim($_POST['usn']);
    $department = trim($_POST['department']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email address!";
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errorMessage = "Passwords do not match!";
    }

    // Check for existing username, email, or USN
    $checkSql = "SELECT * FROM users WHERE username = ? OR email = ? OR usn = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("sss", $username, $email, $usn);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $errorMessage = "Username, email, or USN already exists!";
    }

    // If no errors, proceed with registration
    if (empty($errorMessage)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $insertSql = "INSERT INTO users (username, email, usn, department, password) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("sssss", $username, $email, $usn, $department, $hashedPassword);

        if ($insertStmt->execute()) {
            // Store session data and redirect to dashboard
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['usn'] = $usn;
            header("Location: student_dashboard.php");
            exit;
        } else {
            error_log("Database error: " . $insertStmt->error);
            $errorMessage = "Something went wrong. Please try again later.";
        }

        $insertStmt->close();
    }

    $checkStmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Arapey&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
  <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
  <title>Register Page | BLDEACET Central Library</title>
  <style>
    /* Reset styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #e587df, #9b4d96);
      overflow: hidden; /* Prevent scrollbars */
    }

    .container {
      display: flex;
      width: 100%;
      height: 100%;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      background: #fff;
      animation: fadeIn 1s ease-in-out;
    }

    .left-section {
      flex: 1;
      background: linear-gradient(135deg, #ffffff, rgba(155, 77, 150, 0.3));
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
      animation: slideInLeft 1s ease-in-out;
    }

    .left-section img {
      max-width: 80%;
      z-index: 1;
      position: relative;
    }

    .left-section::before {
      content: "";
      width: 300px;
      height: 300px;
      background: rgba(229, 135, 223, 0.3);
      position: absolute;
      border-radius: 50%;
      bottom: -50px;
      right: -50px;
      animation: pulse 3s infinite;
    }

    .left-section::after {
      content: "";
      width: 200px;
      height: 200px;
      background: rgba(229, 135, 223, 0.2);
      position: absolute;
      border-radius: 50%;
      top: -50px;
      left: -50px;
      animation: pulse 3s infinite;
    }

    .right-section {
      flex: 1;
      padding: 60px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 20px;
      animation: slideInRight 1s ease-in-out;
      overflow-y: auto; /* To avoid content overflow */
    }

    .right-section h1 {
      margin-top: 150px;
      font-family: 'Arapey', sans-serif;
      font-weight: 300; /* Stronger font weight */
      font-size: 3rem; /* Larger font size */
      color: #333;
      overflow-wrap: break-word; /* Ensure heading doesn't overflow */
      text-align: center;
      margin-bottom: 15px;
    }

    .right-section form {
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 100%; /* Full width */
      max-width: 400px; /* To keep it centered and not too wide */
    }

    .right-section span{
        color: #9b4d96;
    }

    form label {
      font-size: 0.9rem;
      color: #555;
    }

    form input, form select {
      padding: 12px;
      font-size: 1rem;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: 0.3s;
    }

    form select {
      color: #000000;
    }

    form select option[value=""] {
      color: gray;
    }


    .switch {
      color: #555;
      text-align: center;
    }

    .switch a {
      color: #9b4d96;
      text-decoration: none;
    }

    .switch a:hover {
      color: #7a3c73;
      text-decoration: underline;
    }

    .btn-primary {
      background: linear-gradient(45deg, #e587df, #9b4d96); /* Purple gradient */
      color: #fff;
      padding: 12px 20px;
      font-size: 1rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #222831, #000000); /* Purple gradient */
    }

      /* Error message style */
      .error-message {
      color: #95122c;
      background:rgb(255, 255, 255, 1); 
      border: 1px solid #95122c; /* Darker red border */
      padding: 15px;
      border-radius: 50px;
      margin-bottom: 20px;
      font-size: 1rem;
      text-align: center;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes slideInLeft {
      from {
        transform: translateX(-100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
        opacity: 0.5;
      }
      50% {
        transform: scale(1.2);
        opacity: 0.8;
      }
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .right-section {
        padding: 20px;
      }

      .right-section h1 {
        font-size: 2.2rem; /* Adjusted for smaller screens */
      }

      form {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-section">
      <img src="undraw_road_to_knowledge_m8s0.svg" alt="Illustration">
    </div>
    <div class="right-section">
      <h1><span>&#10042;</span> Create Account</h1>
      <form action="register.php" method="POST">
      <?php if (!empty($errorMessage)): ?>
          <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="usn">USN:</label>
        <input type="text" id="usn" name="usn" placeholder="Enter your USN" required>

        <label for="department">Department:</label>
        <select id="department" name="department" required>
          <option value="">Select your department</option>
          <option value="Computer Science">Computer Science</option>
          <option value="Electronics">Electronics</option>
          <option value="Mechanical">Mechanical</option>
          <option value="Civil">Civil</option>
          <option value="Electrical">Electrical</option>
        </select>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <label for="confirm-password">Confirm Password:</label>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>

        <button type="submit" class="btn-primary">Register</button>

        <div class="switch">
          <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Function to close the error message
    function closeErrorMessage() {
      document.getElementById("error-message").style.display = 'none';
    }
  </script>
</body>
</html>
