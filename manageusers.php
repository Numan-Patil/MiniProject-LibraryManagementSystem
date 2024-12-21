<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Arapey&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
    <title>Users Info | BLDEACET Central LIbrary</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #222831;
            color: #333;
        }
        header {
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            margin: 0;
            font-size: 2rem;
            color: #e587df;
        }
        .container {
            max-width: 1250px;
            margin: 40px auto;
            background: #f5f5f5;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            overflow: hidden;
            border-radius: 12px;
        }
        table, th, td {
            border: none;
        }
        th {
            background-color: #9b4d96;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 15px;
            font-size: 0.9rem;
        }
        td {
            padding: 15px;
            text-align: left;
            font-size: 0.95rem;
        }
        tr:nth-child(even) {
            background-color: #f7f9fc;
        }
        tr:hover {
            background-color: #eef2fa;
        }
        td:hover {
            color: #9b4d96;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        #refresh-btn {
            margin-top: 20px;
            padding: 12px 25px;
            background: linear-gradient(45deg, #e587df, #9b4d96); /* Purple gradient */
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, background 0.3s ease;
        }
        #refresh-btn:hover {
            transform: scale(1.05);
        }
        #refresh-btn:active {
            transform: scale(0.98);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <header>
        <h1>Users Information</h1>
    </header>
    <div class="container">
        <?php
        // Database connection parameters
        $host = "localhost";
        $username = "root"; // Replace with your DB username
        $password = ""; // Replace with your DB password
        $database = "library_db";

        // Create a connection
        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            echo "<p class='error'>Connection failed: " . $conn->connect_error . "</p>";
        } else {
            // Query to fetch user information
            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h2>List of Users:</h2>";
                echo "<table>";
                echo "<tr>";

                // Fetch and display column headers except 'password'
                $fields = $result->fetch_fields();
                foreach ($fields as $field) {
                    if ($field->name !== 'password') {
                        echo "<th>" . htmlspecialchars($field->name) . "</th>";
                    }
                }
                echo "</tr>";

                // Fetch and display rows of data except for 'password'
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        if ($key !== 'password') {
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                    }
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No users found in the database.</p>";
            }

            // Close connection
            $conn->close();
        }
        ?>
        <button id="refresh-btn">Refresh</button>
    </div>
    <script>
        document.getElementById('refresh-btn').addEventListener('click', function() {
            location.reload(); // Refresh the page when the button is clicked
        });
    </script>
</body>
</html>
