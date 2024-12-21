<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add a new book
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $year = $_POST['year'];
    $status = $_POST['status']; // Book availability status

    $sql_add_book = "INSERT INTO books (title, author, category, year, status) 
                     VALUES ('$title', '$author', '$category', '$year', '$status')";

    if ($conn->query($sql_add_book) === TRUE) {
        echo "New book added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Edit book
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql_edit = "SELECT * FROM books WHERE id = $edit_id";
    $result_edit = $conn->query($sql_edit);
    $book = $result_edit->fetch_assoc();
}

// Update book details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_book'])) {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $year = $_POST['year'];
    $status = $_POST['status']; // Book availability status

    $sql_update_book = "UPDATE books SET title = '$title', author = '$author', category = '$category', year = '$year', status = '$status' WHERE id = $book_id";
    
    if ($conn->query($sql_update_book) === TRUE) {
        echo "Book updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Delete book
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM books WHERE id = $delete_id";
    if ($conn->query($sql_delete) === TRUE) {
        echo "Book deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Check if form is submitted for sending messages
if (isset($_POST['send_notification'])) {
    $message = $_POST['message'];  // Notification message
    $sender = "Admin";  // Sender is always admin in this case

    // Insert notification into the database
    $query = "INSERT INTO messages (message, sender) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $message, $sender);
    $stmt->execute();

    // Redirect or display success message
    echo "Notification sent successfully!";
}

// Log admin login
if (isset($_POST['login'])) {
    // Assuming you have already validated the admin credentials
    $admin_username = $_POST['username'];
    $admin_password = $_POST['password'];

    // Insert login action into admin_history table
    $action = "Admin logged in";
    $query = "INSERT INTO admin_history (action) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $action);
    $stmt->execute();
}

// Fetch all books
$sql_books_list = "SELECT * FROM books ";
$result_books_list = $conn->query($sql_books_list);

// Fetch total number of books
$sql_books = "SELECT COUNT(*) AS total_books FROM books";
$result_books = $conn->query($sql_books);
$total_books = $result_books->fetch_assoc()['total_books'];

// Fetch total number of users
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$result_users = $conn->query($sql_users);
$total_users = $result_users->fetch_assoc()['total_users'];

// Fetch number of pending requests (assuming a 'pending' column in users table)
$sql_pending = "SELECT COUNT(*) AS pending_requests FROM users WHERE status = 'pending'";
$result_pending = $conn->query($sql_pending);
$pending_requests = $result_pending->fetch_assoc()['pending_requests'];

// Fetch activity history (admin logs)
$sql_history = "SELECT * FROM history ORDER BY timestamp DESC LIMIT 5";
$result_history = $conn->query($sql_history);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | BLDEACET Central Library</title>
    <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
    <link rel="stylesheet" href="admin.css?v=2.0">
    <link href="https://fonts.googleapis.com/css2?family=Arapey&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
        <h3>Admin Dashboard</h3>
            <img src="Header.svg" alt="Library Logo">
        </div>
        <hr>
        <ul>
            <li><a href="#dashboard" class="active">Dashboard</a></li>
            <li><a href="manageusers.php">Manage Users</a></li>
            <li><a href="#books">Manage Books</a></li>
            <li><a href="#sendNotification">Send messages</a></li>
            <li><a href="notification.php">MailBox<sup><span style="color: red;"> <strong>&#x2022;</strong></span></sup></a></li>
            <li><a href="index.php">Log Out</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Search Bar -->
        <div class="search-container">
            <div class="search-bar">
                <div class="search-bar-inner">
                    <input type="text" placeholder="&#10024; Search Admin Tools...">
                </div>
            </div>
        </div>

        <!-- Dashboard Section -->
        <section id="dashboard">
            <h2>Dashboard</h2>
            <div class="stats">
                <div class="stat-card">
                    <h3>Total Books</h3>
                    <p><span><?php echo $total_books; ?>+</span></p>
                </div>
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p><span><?php echo $total_users; ?>+</span></p>
                </div>
                <div class="stat-card">
                    <h3>Pending Requests</h3>
                    <p><span><?php echo $pending_requests; ?></span></p>
                </div>
            </div>
        </section>

        <!-- Manage Books Section -->
        <section id="books">
            <h2>Manage Books</h2>
            <!-- Add Book Form -->
            <h3>Add New Book</h3>
            <form method="POST" action="">
                <input type="text" name="title" placeholder="Book Title" required>
                <input type="text" name="author" placeholder="Author" required>
                <input type="text" name="category" placeholder="Category" required>
                <input type="number" name="year" placeholder="Year" required>
                <input type="text" name="status" placeholder="Status" required>
                <button type="submit" name="add_book">Add Book</button>
            </form>

            <h3>Existing Books</h3>
            <div class="book-list">
                <?php
                // Display books with options to edit and delete
                while ($book = $result_books_list->fetch_assoc()) {
                    echo '<div class="book-card">';
                    echo '<h3>' . $book['title'] . '</h3>';
                    echo '<p>Author: ' . $book['author'] . '</p>';
                    echo '<p>Category: ' . $book['category'] . '</p>';
                    echo '<p>Year: ' . $book['year'] . '</p>';
                    echo '<p>Status: ' . $book['status'] . '</p>';
                    echo '<a href="?edit_id=' . $book['id'] . '">Edit</a>';
                    echo '<a href="?delete_id=' . $book['id'] . '" onclick="return confirm(\'Are you sure you want to delete this book?\')">Delete</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </section>

        <!-- Edit Book Form (if editing) -->
        <?php
        if (isset($book)) {
            echo '<h3>Edit Book</h3>';
            echo '<form method="POST" action="">';
            echo '<input type="hidden" name="book_id" value="' . $book['id'] . '">';
            echo '<input type="text" name="title" value="' . $book['title'] . '" required>';
            echo '<input type="text" name="author" value="' . $book['author'] . '" required>';
            echo '<input type="text" name="category" value="' . $book['category'] . '" required>';
            echo '<input type="number" name="year" value="' . $book['year'] . '" required>';
            echo '<input type="text" name="status" value="' . $book['status'] . '" required>';
            echo '<button type="submit" name="update_book">Update Book</button>';
            echo '</form>';
        }
        ?>

        <!-- Send Notification Form -->
<div id="sendNotification" class="send-notification">
    <h3>Send Notification</h3>
    <form method="POST">
    <label for="message">Notification Message:</label>
    <textarea name="message" id="message" rows="4" required></textarea>
    <button type="submit" name="send_notification">Send Notification</button>
    </form>
</div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTopBtn" title="Go to top"><b>&#8593;</b></button>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default anchor behavior
                const targetId = this.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);

                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        window.onscroll = function () {
            const backToTopBtn = document.getElementById('backToTopBtn');
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backToTopBtn.style.display = "block";
            } else {
                backToTopBtn.style.display = "none";
            }
        };
    </script>
</body>
</html>
