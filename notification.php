<?php
// Include the connection to your database
include('db_connect.php');

// Fetch notifications (name, email, message, id) from the contact_messages table
$query = "SELECT cm.id, cm.name, cm.email, cm.message FROM contact_messages cm ORDER BY cm.id DESC"; 
$result = mysqli_query($conn, $query);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Delete notifications if requested
if (isset($_POST['delete_notifications'])) {
    if (!empty($_POST['selected_notifications'])) {
        $selected_ids = implode(',', $_POST['selected_notifications']);
        
        // Delete selected notifications from the database
        $delete_query = "DELETE FROM contact_messages WHERE id IN ($selected_ids)";
        $delete_result = mysqli_query($conn, $delete_query);

        if ($delete_result) {
            echo "<p id='success-message' style='color: #c7eec3;'>Selected notifications have been deleted successfully.</p>";
        } else {
            echo "<p id='error-message' style='color: red;'>Failed to delete notifications. Please try again.</p>";
        }
    } else {
        echo "<p id='error-message' style='color: red;'>No notifications selected for deletion.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
    <title>Notifications | BLDEACET Central Library</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Arapey&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Body Styling */
        body {
            background: #222831;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Page Heading */
        .page-heading {
            text-align: center;
            font-weight: 500;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #4A4A4A;
        }

        /* Notifications Container */
        .notifications-container {
            width: 100%;
            max-width: 1300px;
            margin: 10px auto;
            background: #f5f5f5;
            padding: 20px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Notification Styles */
        .notification {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #fff;
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative; /* Added to allow positioning of checkbox */
        }

        .notification:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .notification h4 {
            font-family: 'Poppins', serif;
            font-size: 1rem;
            color: #222831;
            margin: 0;
        }

        .notification span{
            font-family: 'Arapey', serif;
            font-weight: 500;
            font-size: 1.2rem;
            color: #333;
            margin: 0;
        }

        .notification p {
            font-size: 0.95rem;
            color: #555;
        }

        .notification p strong {
            color: #9b4d96; /* Highlight strong labels */
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .page-heading {
                font-size: 1.5rem;
            }

            .notifications-container {
                padding: 15px 20px;
            }
        }

        /* Checkbox Styling */
        .notification input[type="checkbox"] {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            accent-color: #9b4d96;
            cursor: pointer;
            display: none; /* Initially hide checkboxes */
        }

        /* Delete and Select Buttons Styling */
        .delete-btn {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #95122c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .select-btn {
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #9b4d96;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="notifications-container">
        <h1 class="page-heading"><b>// </b>Catch Up Notifications <span style="color: #9b4d96;">&#10042;</span> </h1>

        <!-- Button to toggle visibility of checkboxes -->
        <?php if (mysqli_num_rows($result) > 0): ?>
            <button class="select-btn" onclick="toggleCheckboxes()">Select Notifications</button>
            <form method="POST">
                <?php
                // Display notifications from the database
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='notification'>";
                    echo "<input type='checkbox' name='selected_notifications[]' value='" . $row['id'] . "' class='notification-checkbox'>";
                    echo "<h4><span> Message from : </span>" . htmlspecialchars($row['name']) . "</h4>";
                    echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                    echo "<p><strong>Message:</strong> " . htmlspecialchars($row['message']) . "</p>";
                    echo "</div>";
                }
                ?>
                <button type="submit" name="delete_notifications" class="delete-btn">Delete Selected Notifications</button>
            </form>
        <?php else: ?>
            <p style="text-align:center; color: #777;">No new messages found.</p>
        <?php endif; ?>
    </div>

    <script>
        // JavaScript to toggle visibility of checkboxes
        function toggleCheckboxes() {
            const checkboxes = document.querySelectorAll('.notification-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.style.display = checkbox.style.display === 'none' ? 'block' : 'none';
            });
        }

                // Function to hide success and error messages after 3 seconds
                function hideMessage() {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');

            if (successMessage) {
                setTimeout(() => successMessage.style.display = 'none', 3000);
            }

            if (errorMessage) {
                setTimeout(() => errorMessage.style.display = 'none', 3000);
            }
        }

        // Call hideMessage function on page load
        window.onload = hideMessage;

    </script>
</body>
</html>
