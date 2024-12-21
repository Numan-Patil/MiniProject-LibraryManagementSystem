<?php
// Connecting to the Database
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

// Fetching books based on categories
function fetchBooks($category = '')
{
    global $conn;
    $sql = "SELECT * FROM books";
    if ($category) {
        $sql .= " WHERE category = ?";
    }
    $stmt = $conn->prepare($sql);
    if ($category) {
        $stmt->bind_param("s", $category);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    return $books;
}
// Handle borrowing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'], $_POST['usn'])) {
    $bookId = $_POST['book_id'];
    $usn = $_POST['usn'];
    $borrowedDate = date("Y-m-d");
    $dueDate = date("Y-m-d", strtotime("+7 days"));

    // Fetch the book title
    $sqlBook = "SELECT title FROM books WHERE id = ?";
    $stmtBook = $conn->prepare($sqlBook);
    $stmtBook->bind_param("i", $bookId);
    $stmtBook->execute();
    $result = $stmtBook->get_result();
    $book = $result->fetch_assoc();
    
    if ($book) {
        // Add entry to Borrowed_books
        $sqlBorrowed = "INSERT INTO Borrowed_books (usn, book_id, borrowed_at, book_title, due_date) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmtBorrowed = $conn->prepare($sqlBorrowed);
        $stmtBorrowed->bind_param("issss", $usn, $bookId, $borrowedDate, $book['title'], $dueDate);
        $stmtBorrowed->execute();

        // Update book status to borrowed
        $sqlUpdate = "UPDATE books SET status = 'borrowed' WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("i", $bookId);
        $stmtUpdate->execute();

        echo json_encode(["status" => "success", "message" => "Book borrowed successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Book not found"]);
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
    <title>Course Books | BLDEACET Central Library</title>
    <link rel="stylesheet" href="course_books.css?v=3.0">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Arapey&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar Menu -->
    <nav class="sidebar" id="sidebar">
        <div class="logo" id="logo">
            <h3>SELECT <img src="Header.svg" alt="Library-image"> DEPARTMENT</h3>
        </div>
        <br>
        <hr>
        <ul>
            <li><a href="#all-books">Common For All</a></li>
            <li class="dropdown">
                <a href="#" class="toggle-btn">Computer Science and Sub Branches</a>
                <ul class="dropdown-list">
                    <li><a href="#cs">Computer Science (CS)</a></li>
                    <li><a href="#ai">Artificial Intelligence (AI)</a></li>
                    <li><a href="#is">Information Science (IS)</a></li>
                    <li><a href="#ds">Data Science (DS)</a></li>
                </ul>
            </li>
            <li><a href="#civil">Civil</a></li>
            <li><a href="#electrical">Electrical</a></li>
            <li><a href="#electronics">Electronics</a></li>
            <li><a href="#mechanical">Mechanical</a></li>
            <li><a href="#others">Others</a></li>
        </ul>
    </nav>

    <!-- Header Image-->
    <div class="Header-container">
        <div class="Header">
            <img src="Course_Header.svg" alt="Course_Header">
            <div class="Header-text">
                <h1 id="quote">Welcome to BLDEACET Library</h1>
                <p id="author">Explore Knowledge and Resources</p>
            </div>
        </div>
    </div>

    <!-- Search Bar Section -->
    <div class="search-container">
        <div class="search-bar">
            <div class="search-bar-inner">
                <form action="" method="GET">
                    <input type="text" id="searchInput" name="search" placeholder=" &#10024; Search Course Books and More..."" aria-label="Search Course Books">
                </form>
            </div>
        </div>
        <!-- Filter Buttons -->
        <div class="filter-buttons">
            <button class="filter-btn" onclick="filterBy('author')">Author</button>
            <button class="filter-btn" onclick="filterBy('year')">Year</button>
            <button class="filter-btn" onclick="filterBy('category')">Category</button>
        </div>
    </div>


    <!-- Main Page Content -->
    <div class="main-content" id="main-content">
        <section id="all-books">
            <h2><b>//</b> Common For All <span>&#10042;</span></h2>
            <p>Browse all available course books in the library.</p>
            <div class="book-list">
                <?php
                $allBooks = fetchBooks('Common');
                foreach ($allBooks as $book) {
                    echo "<div class='book-card'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($book['category']) . "</p>";
                    echo "<p>Year: " . htmlspecialchars($book['year']) . "</p>";
                    echo "<button class='borrow-btn' onclick='borrowBook(" . $book['id'] . ")'>Borrow</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>

        <section id="cs">
            <h2><b>//</b> Computer Science <span>&#10042;</span></h2>
            <p>Explore course books and resources for Computer Science.</p>
            <div class="book-list">
                <?php
                $csBooks = fetchBooks('Computer Science');
                foreach ($csBooks as $book) {
                    echo "<div class='book-card'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($book['category']) . "</p>";
                    echo "<p>Year: " . htmlspecialchars($book['year']) . "</p>";
                    echo "<button class='borrow-btn' onclick='borrowBook(" . $book['id'] . ")'>Borrow</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>

        <section id="ai">
            <h2><b>//</b> Artificial Intelligence <span>&#10042;</span></h2>
            <p>Explore course books and resources for Artificial Intelligence.</p>
            <div class="book-list">
                <?php
                $csBooks = fetchBooks('Artificial Intelligence');
                foreach ($csBooks as $book) {
                    echo "<div class='book-card'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($book['category']) . "</p>";
                    echo "<p>Year: " . htmlspecialchars($book['year']) . "</p>";
                    echo "<button class='borrow-btn' onclick='borrowBook(" . $book['id'] . ")'>Borrow</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>

        <section id="is">
            <h2><b>//</b> Information Science <span>&#10042;</span></h2>
            <p>Explore course books and resources for Information Science.</p>
            <div class="book-list">
                <?php
                $csBooks = fetchBooks('Information Science');
                foreach ($csBooks as $book) {
                    echo "<div class='book-card'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($book['category']) . "</p>";
                    echo "<p>Year: " . htmlspecialchars($book['year']) . "</p>";
                    echo "<button class='borrow-btn' onclick='borrowBook(" . $book['id'] . ")'>Borrow</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>

        <section id="ds">
            <h2><b>//</b> Data Science <span>&#10042;</span></h2>
            <p>Explore course books and resources for Data Science.</p>
            <div class="book-list">
                <?php
                $csBooks = fetchBooks('Data Science');
                foreach ($csBooks as $book) {
                    echo "<div class='book-card'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($book['category']) . "</p>";
                    echo "<p>Year: " . htmlspecialchars($book['year']) . "</p>";
                    echo "<button class='borrow-btn' onclick='borrowBook(" . $book['id'] . ")'>Borrow</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>

        <section id="civil">
            <h2><b>//</b> Civil <span>&#10042;</span></h2>
            <p>Explore course books and resources for Civil.</p>
            <div class="book-list">
                <?php
                $csBooks = fetchBooks('Civil');
                foreach ($csBooks as $book) {
                    echo "<div class='book-card'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($book['category']) . "</p>";
                    echo "<p>Year: " . htmlspecialchars($book['year']) . "</p>";
                    echo "<button class='borrow-btn' onclick='borrowBook(" . $book['id'] . ")'>Borrow</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>
    
        <section id="electrical">
            <h2><b>//</b> Electrical <span>&#10042;</span></h2>
            <p>Explore course books and resources for Electrical.</p>
            <div class="book-list">
                <?php
                $csBooks = fetchBooks('Electrical');
                foreach ($csBooks as $book) {
                    echo "<div class='book-card'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($book['category']) . "</p>";
                    echo "<p>Year: " . htmlspecialchars($book['year']) . "</p>";  
                    echo "<button class='borrow-btn' onclick='borrowBook(" . $book['id'] . ")'>Borrow</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>

        <section id="electronics">
            <h2><b>//</b> Electronics <span>&#10042;</span></h2>
            <p>Explore course books and resources for Electronics.</p>
            <div class="book-list">
                <?php
                $csBooks = fetchBooks('Electronics');
                foreach ($csBooks as $book) {
                    echo "<div class='book-card'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($book['category']) . "</p>";
                    echo "<p>Year: " . htmlspecialchars($book['year']) . "</p>";
                    echo "<button class='borrow-btn' onclick='borrowBook(" . $book['id'] . ")'>Borrow</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>

        <section id="mechanical">
            <h2><b>//</b> Mechanical <span>&#10042;</span></h2>
            <p>Explore course books and resources for Mechanical.</p>
            <div class="book-list">
                <?php
                $csBooks = fetchBooks('Mechanical');
                foreach ($csBooks as $book) {
                    echo "<div class='book-card'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($book['category']) . "</p>";
                    echo "<p>Year: " . htmlspecialchars($book['year']) . "</p>";
                    echo "<button class='borrow-btn' onclick='borrowBook(" . $book['id'] . ")'>Borrow</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>

        <section id="others">
            <h2><b>//</b> Others <span>&#10042;</span></h2>
            <p>Explore Other course books and resources.</p>
            <div class="book-list">
                <?php
                $csBooks = fetchBooks('Others');
                foreach ($csBooks as $book) {
                    echo "<div class='book-card'>";
                    echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
                    echo "<p>Author: " . htmlspecialchars($book['author']) . "</p>";
                    echo "<p>Category: " . htmlspecialchars($book['category']) . "</p>";
                    echo "<p>Year: " . htmlspecialchars($book['year']) . "</p>";
                    echo "<button class='borrow-btn' onclick='borrowBook(" . $book['id'] . ")'>Borrow</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>

    </div>

    <script>
// Add toggle functionality for dropdown menus
document.addEventListener('DOMContentLoaded', () => {
    const dropdown = document.querySelector('.dropdown');
    const toggleButton = dropdown.querySelector('.toggle-btn');

    toggleButton.addEventListener('click', () => {
        dropdown.classList.toggle('open');
    });
});

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default anchor behavior
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);

        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
            // Update URL without adding to history
            history.replaceState(null, null, `#${targetId}`);
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const quotes = [
    { quote: "Welcome to BLDEACET Central Library", author: "Explore Knowledge and Resources" },
    { quote: "A room without books is like a body without a soul.", author: "Marcus Tullius Cicero" },
    { quote: "Books are a uniquely portable magic.", author: "Stephen King" },
    { quote: "The only thing that you absolutely have to know is the location of the library.", author: "Albert Einstein" },
    { quote: "So many books, so little time.", author: "Frank Zappa" },
    { quote: "Reading gives us someplace to go when we have to stay where we are.", author: "Mason Cooley" },
    { quote: "A book is a dream that you hold in your hand.", author: "Neil Gaiman" },
    { quote: "The best books... are those that tell you what you know already.", author: "George Orwell" },
    { quote: "No two persons ever read the same book.", author: "Edmund Wilson" },
    { quote: "Libraries were full of ideas - perhaps the most dangerous and powerful of all weapons.", author: "Sarah J. Maas" },
    { quote: "Books are the plane, and the train, and the road. They are the destination and the journey. They are home.", author: "Anna Quindlen" },
    { quote: "I have always imagined that Paradise will be a kind of library.", author: "Jorge Luis Borges" },
    { quote: "If you only read the books that everyone else is reading, you can only think what everyone else is thinking.", author: "Haruki Murakami" },
    { quote: "Once you learn to read, you will be forever free.", author: "Frederick Douglass" },
    { quote: "Books are mirrors: you only see in them what you already have inside you.", author: "Carlos Ruiz Zafón" },
    { quote: "Until I feared I would lose it, I never loved to read. One does not love breathing.", author: "Harper Lee" },
    { quote: "I read a book one day and my whole life was changed.", author: "Orhan Pamuk" },
    { quote: "A good book is an event in my life.", author: "Stendhal" },
    { quote: "There is only one thing that makes a dream impossible to achieve: the fear of failure.", author: "Paulo Coelho" },
    { quote: "You can never get a cup of tea large enough or a book long enough to suit me.", author: "C.S. Lewis" },
    { quote: "There is no friend as loyal as a book.", author: "Ernest Hemingway" },
    { quote: "We read to know we're not alone.", author: "William Nicholson" },
    { quote: "Reading is essential for those who seek to rise above the ordinary.", author: "Jim Rohn" },
    { quote: "Books are the treasured wealth of the world and the fit inheritance of generations and nations.", author: "Henry David Thoreau" },
    { quote: "The reading of all good books is like a conversation with the finest minds of past centuries.", author: "René Descartes" },
    { quote: "It is what you read when you don't have to that determines what you will be when you can't help it.", author: "Oscar Wilde" },
    { quote: "Reading brings us unknown friends.", author: "Honoré de Balzac" },
    { quote: "I cannot sleep unless I am surrounded by books.", author: "Jorge Luis Borges" },
    { quote: "You can never be overdressed or overeducated.", author: "Oscar Wilde" },
    { quote: "I Designed This Website", author: "Numan Patil" }
];

let currentIndex = 0;

function displayQuote() {
    const quoteElement = document.getElementById("quote");
    const authorElement = document.getElementById("author");

    // Update the text
    quoteElement.textContent = quotes[currentIndex].quote;
    authorElement.textContent = `${quotes[currentIndex].author}`;

    // Move to the next quote, loop back to the start if necessary
    currentIndex = (currentIndex + 1) % quotes.length;
}

// Initialize the first quote
displayQuote();

// Change the quote every 5 seconds
setInterval(displayQuote, 5000);
});

// Function to handle book borrowing
function borrowBook(bookId) {
    // Get user USN (this should come from session or user input)
    const usn = prompt("Enter your USN:");
    if (!usn) {
        alert("USN is required to borrow a book.");
        return;
    }

    // Show confirmation before proceeding
    const confirmation = confirm(`Are you sure you want to borrow the book with ID: ${bookId}?`);
    if (!confirmation) {
        return;
    }

    // Send POST request to borrow book
    fetch("borrow_book.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `book_id=${bookId}&usn=${usn}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Book borrowed successfully!");

            // Disable the borrow button after successful borrowing
            const borrowButton = document.getElementById(`borrow-btn-${bookId}`);
            if (borrowButton) {
                borrowButton.disabled = true;
                borrowButton.textContent = "Already Borrowed";
            }
        } else {
            alert("Failed to borrow book: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Book Borrowed Sucessfully Collect it Within the next 24 Hrs from the Library Reception.");
    });
}

    </script>
</body>
</html>
