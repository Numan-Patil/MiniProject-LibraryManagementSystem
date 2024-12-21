
<?php
// contact.php

// Include the connection to your database
include('db_connect.php');  // Assuming you have a 'db_connect.php' file for database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Check if fields are not empty
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Insert the contact details into your database
        $query = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$message')";
        if (mysqli_query($conn, $query)) {
            // If insertion is successful, trigger a notification
            $notificationQuery = "INSERT INTO notifications (user_id, message) VALUES (1, 'New contact message from $name')";
            mysqli_query($conn, $notificationQuery);  // Assuming the admin's user_id is 1

            // Respond with a success message
            echo "<script>
                alert('Message sent successfully! We will get back to you shortly.');
                window.location.href = 'index.php';  // Redirect back to contact form or desired page
            </script>";
        } else {
            // If there's an error
            echo "<script>
                alert('Error submitting the form. Please try again.');
            </script>";
        }
    } else {
        echo "<script>
            alert('All fields are required!');
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | BLDEACET Central Library</title>
    <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
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
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            opacity: 0;
            animation: fadeIn 1.5s forwards;
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        html {
            scroll-behavior: smooth;
        }

        /* Main Content */
        .main-content {
            width: 100%;
            max-width: 1200px;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 40px;
            padding: 0 20px;
        }

        /* Page Heading */
        .page-heading {
            text-align: center;
            font-family: 'Arapey', serif;
            font-weight: 100;
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #e587df;
        }

        .page-heading span{
            font-family: 'Arapey',sans-serif;
            color: #fff;
        }

        /* Section Styling */
        section {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        section:hover {
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        section h2 {
            font-family: 'Arapey', serif;
            font-weight: 100;
            margin-bottom: 15px;
            font-size: 2rem;
            color: #333;
        }

        section p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        span{
            font-family: 'Arapey',sans-serif;
            color: #e587df;
        }

        /* Contact Form Styles */
        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .contact-form input,
        .contact-form textarea {
            padding: 15px;
            border: 2px solid #9b4d96;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
            transition: border 0.3s ease;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: #7a3c72;
            outline: none;
        }

        .contact-form button {
            padding: 15px;
            background: linear-gradient(45deg, #e587df, #9b4d96); /* Purple gradient */
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .contact-form button:hover {
            background: linear-gradient(45deg, #222831, #222831); /* Purple gradient */
        }

        /* Footer Styling */
        footer {
            background-color: #222831;
            color: white;
            width: 100%;
            padding: 60px 20px;
            text-align: left;
            margin-top: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-left {
            flex: 1;
            max-width: 600px;
        }

        .footer-left h3 {
            font-family: 'Arapey', serif;
            font-weight: 100;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .footer-left p {
            font-size: 16px;
            margin-bottom: 12px;
            color: #ddd;
        }

        .footer-left a {
            color: #9b4d96;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-left a:hover {
            text-decoration: underline;
        }

        .map-container {
            flex: 1;
            display: flex;
            justify-content: flex-end;
        }

        iframe {
            border-radius: 10px;
            width: 1180px;
            height: 250px;
            transition: all 0.3s ease;
        }

        /* Success message styles */
        .success-message {
            display: none;
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-weight: bold;
        }

        .error-message {
            display: none;
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-weight: bold;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .footer-left {
                text-align: center;
                margin-bottom: 20px;
            }

            .map-container {
                justify-content: center;
                margin-top: 20px;
            }

            iframe {
                width: 100%;
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <!-- Page Heading -->
    <div class="page-heading">
        <h1><span>&#10042;</span>Contact Us</h1>
    </div>
    <div class="main-content">
        <section id="form-section">
            <h2><span>&#10042;</span> Get in Touch</h2>
            <p>We would love to hear from you. Please fill out the form below.</p>
            <form class="contact-form" action="contact.php" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-left">
            <h3><span>&#10042;</span> Location</h3>
            <p><b>BLDEACET Central Library</b></p>
            <p>Vijayapura, Karnataka, India</p>
            <p><b>Contact:</b> 08352-261120</p>
            <p><b>Fax:</b> 08352-262945</p>
            <p><b>Email:</b> principal@bldeacet.ac.in</p>
            <p><b>Web:</b> <a href="http://bldeacet.ac.in" target="_blank">bldeacet.ac.in</a></p>
        </div>
        <div class="map-container">
            <!-- Embed Google Map -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15274.215654423784!2d75.717493!3d16.848472000000005!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc6558982df81f7%3A0x589f36a4e9dc6fab!2sBLDEA&#39;s%20V%20P%20Dr%20PG%20Halakatti%20College%20of%20Engineering%20%26%20Technology!5e0!3m2!1sen!2sin!4v1729619144015!5m2!1sen!2sin" title="Google Map showing BLDEACET Central Library location" alt="Google Map showing BLDEACET Central Library location"></iframe>
        </div>
    </footer>

    <!-- JavaScript for form submission -->
    <script>
            document.querySelector('.contact-form').addEventListener('submit', function(event) {
        // Let the form submission be handled by PHP after validation
        // No need for JavaScript logic for form validation here since PHP handles it
    });
    </script>
</body>
</html>
