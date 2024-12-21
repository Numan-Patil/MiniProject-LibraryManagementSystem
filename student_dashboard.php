<?php
// Start the session and check if the student is logged in
session_start();

if (!isset($_SESSION['usn'])) {
    echo "<div style='
        font-family: Poppins, sans-serif;
        font-size: 18px;
        color: #ffffff;
        background: linear-gradient(145deg, #e587df, #9b4d96);
        padding: 30px 40px;
        border-radius: 20px 40px;
        width: 1200px;
        margin: 50px auto;
        text-align: center;
    '><b> Access Denied !</b><br>You need to be logged in to access this page</div>";

    // Add your image here
    echo "<div style='text-align: center; margin: 20px 0;'>
            <img src='undraw_fingerprint-login_19qv.svg' alt='Access Denied Image' style='max-width: 45%; height: 45%;'>
          </div>";

    echo "<div style='
        font-family: Poppins, sans-serif;
        font-weight:600;
        font-size: 12px;
        color: #ffffff;
        background: linear-gradient(145deg, #e587df, #9b4d96);
        padding: 15px 20px;
        border-radius: 50px;
        width: 250px;
        margin: 50px auto;
        text-align: center;
    '><a href='login.php' style='text-decoration: none; color: #ffffff; display: block;'>Click Here To Login</a></div>";
    
    exit;
}


// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use usn instead of student_id
$usn = $_SESSION['usn'];

// Fetch student data
$stmt = $conn->prepare("SELECT name, books_borrowed, overdue_books FROM students WHERE usn = ?");
$stmt->bind_param("s", $usn); // usn is a string, so use "s"
$stmt->execute();
$result = $stmt->get_result();

// Check if student data exists
if ($result->num_rows > 0) {
    $student_data = $result->fetch_assoc();
} else {
    $student_data = null; // No data found
}

// Fetch borrowed books from borrowed_books table
$stmt_books = $conn->prepare("SELECT book_title, due_date FROM borrowed_books WHERE usn = ?");
$stmt_books->bind_param("s", $usn);
$stmt_books->execute();
$books_result = $stmt_books->get_result();

// Fetch messages from the messages table
$query_messages = "SELECT message, created_at FROM messages ORDER BY created_at DESC";
$messages_result = $conn->query($query_messages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
    <title>Student Dashboard | BLDEACET Central Library</title>
    <link rel="stylesheet" href="student_dashboard.css?v=3.0">
    <link href="https://fonts.googleapis.com/css2?family=Arapey&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar Menu -->
    <nav class="sidebar">
        <div class="logo">
            <h3>BLDEACET Library</h3>
            <img src="Header.svg" alt="Library Image">
            <p>Department: Computer Science</p>
        </div>  
        <hr>  
        <ul>
            <li><a href="#search-books">Search Books</a></li>
            <li><a href="#dashboard" class="active">Dashboard</a></li>
            <li><a href="#notifications">Notifications <sup><span style="color: red;"> <strong>&#x2022;</strong></span></sup></a></li>
            <li><a href="#my-books">My Books</a></li>
            <li><a href="#borrow-history">Borrow History</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="#help">Help & Support</a></li>
        </ul>
    </nav>        

    <!-- Main Page Content -->
    <div class="main-content">

    <!-- Search Bar Section -->
    <div class="search-container " id="#search-books">
        <div class="search-bar">
            <div class="search-bar-inner">
                <form action="" method="GET">
                    <input type="text" id="searchInput" name="search" placeholder=" &#10024; Search Dashboard...."" aria-label="Search Course Books">
                </form>
            </div>
        </div>
    </div>

        <!-- Dashboard Section -->
        <section id="dashboard">
            <h2><b>//</b> Dashboard <span>&#10042;</span></h2>
            <?php
            if ($student_data) {
                echo "<p>Welcome back, " . htmlspecialchars($student_data['name']) . "! Here is an overview of your library activity.</p>";
            } else {
                echo "<p>Student data not found. Please contact the administrator.</p>";
            }
            ?>
            <div class="stats">
                <div class="stat-card">
                    <h3>Books Borrowed</h3>
                    <p><?php echo $student_data ? htmlspecialchars($student_data['books_borrowed']) : '3+'; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Overdue Books</h3>
                    <p><?php echo $student_data ? htmlspecialchars($student_data['overdue_books']) : 'N/A'; ?></p>
                </div>
            </div>
        </section>

        <!-- Messages Section -->
        <section id="notifications">
            <h2><b>//</b> Notifications <span>&#10042;</span></h2>
            <p>Stay updated with the latest library updates and reminders.</p>
            <ul class="notification-list">
                <?php 
                if ($messages_result->num_rows > 0) {
                    while ($row = $messages_result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($row['message']) . " (" . $row['created_at'] . ")</li>";
                    }
                } else {
                    echo "<li>No notifications available.</li>";
                }
                ?>
            </ul>
        </section>

<!-- My Books Section --> 
<section id="my-books">
    <h2><b>//</b> My Books <span>&#10042;</span></h2>
    <p>Here are the books currently borrowed by you.</p>
    <div class="book-list">
        <?php 
        // Simulate fetching books (use real database query here)
        // Example books data
        $books = [
            ['book_title' => 'Machine Learning: Advanced Concepts', 'author' => 'Dr. John Harris', 'due_date' => '2025-04-15'],
            ['book_title' => 'Data Structures: Theoretical and Practical Applications', 'author' => 'Theor Wilson', 'due_date' => '2025-01-10'],
            ['book_title' => 'Operating Systems: Complete Reference', 'author' => 'Cleo Abram', 'due_date' => '2025-09-22']
        ];

        // Display books (assuming $books is an array from the database)
        if (!empty($books)) {
            foreach ($books as $row) {
                echo "<div class='book-card' onclick='markAsBorrowed(this)'>
                        <h3>" . htmlspecialchars($row['book_title']) . "</h3>
                        <p>Author: " . htmlspecialchars($row['author']) . "</p>
                        <p>Due Date: " . $row['due_date'] . "</p>
                        <button class='borrow-btn'>Borrowed</button>
                    </div>";
            }
        } else {
            echo "<p>You have no borrowed books.</p>";
        }
        ?>
    </div>
</section>

        <!-- Borrow History Section -->
        <section id="borrow-history">
            <h2><b>//</b> Borrow History <span>&#10042;</span></h2>
            <p>Check your past borrow history.</p>
            <ul class="history-list">
                <?php 
                $stmt_history = $conn->prepare("SELECT book_title, borrowed_date, returned_date FROM borrow_history WHERE usn = ?");
                $stmt_history->bind_param("s", $usn);
                $stmt_history->execute();
                $history_result = $stmt_history->get_result();

                if ($history_result->num_rows > 0) {
                    while ($history = $history_result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($history['book_title']) . ": Borrowed on " . $history['borrowed_date'] . ", Returned on " . $history['returned_date'] . "</li>";
                    }
                } else {
                    echo "<li>Borrowed Books : 3.</li>";
                }
                ?>
            </ul>
        </section>

        <!-- Help Section -->
        <section id="help">
            <h2><b>//</b> Help & Support <span>&#10042;</span></h2>
            <p><a href="contact.php">Contact the librarian or access the FAQ for assistance.</a></p>
        </section>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTopBtn" title="Go to top"><b>&#8593;</b></button>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);

                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    history.replaceState(null, null, `#${targetId}`);
                }
            });
        });

        let backToTopBtn = document.getElementById("backToTopBtn");

        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backToTopBtn.style.display = "block";
            } else {
                backToTopBtn.style.display = "none";
            }
        }

        backToTopBtn.onclick = function() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
