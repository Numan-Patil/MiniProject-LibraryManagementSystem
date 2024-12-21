<?php
// Include the Header Section
function renderHeader() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="BLDEACET Central Library provides resources and services for students and staff, including course books, journals, and more.">
        <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
        <title>BLDEACET Central Library</title>
        <link rel="stylesheet" href="style.css?v=5.0">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Arapey&family=Poppins:wght@400;500&display=swap" rel="stylesheet">    
    </head>
    <body>
        <div class="navbar-container">
            <div class="hamburger" id="hamburger">
                <img src="https://img.icons8.com/?size=30&id=ZEbhxDiRoZD1&format=png&color=ffffff" alt="Menu">
            </div>
            
            <!-- Sidebar Menu -->
            <nav class="sidebar" id="sidebar">
                <div class="logo" id="logo">
                    <img src="bldea_logo.webp" alt="Library Logo"> <!-- logo path -->
                    <h3>BLDEACET</h3>
                </div>  
                <br>
                <hr>  
                <ul>
                    <li><a href="course_books.php">Course Books</a></li>
                    <li><a href="miscellaneous.html">Miscellaneous</a></li>
                    <li><a href="commingsoon.html">Classrooms</a></li>
                    <li><a href="commingsoon.html">Journals and Publications</a></li>
                    <li><a href="commingsoon.html">Research Papers</a></li>
                    <li><a href="student_dashboard.php">Student Dashboard</a></li>
                    <li><a href="secure.php">Admin Dashboard</a></li>
                </ul>
            </nav>        
            <nav class="navbar">
                <a href="index.php" class="nav-link">Home</a>
                <a href="about.html" class="nav-link">About</a>
                <a href="gallery.html" class="nav-link">Gallery</a>
                <a href="contact.php" class="nav-link">Contact</a>
            </nav>
            <a href="login.php" class="login-btn"><span>Log in</span></a>
        </div>
    <?php
}

// Include the Footer Section
function renderFooter() {
    ?>
<!-- Footer Section -->
<footer class="footer">
    <div class="footer-container">
        <!-- Footer Left Section -->
        <div class="footer-left">
            <div class="footer-brand">
                <h2>&#10042; BLDEACET Central Library</h2>
                <h4>For those who believe there is no substitute for excellence</h4>
                <br>
                <p>&copy; 2024 All rights reserved. | CET CODE - E038</p>
            </div>

            <nav class="footer-nav">
                <a href="about.html">About Us</a>
                <a href="contact.html">Contact</a>
                <a href="privacy.html">Privacy Policy</a>
            </nav>
        </div>

        <!-- Footer Center Section: Social Media Links -->
        <div class="footer-center">
            <h3>Follow Us</h3>
            <div class="social-media">
                <a href="https://www.facebook.com/bldeacetmedia/" aria-label="Facebook" class="social-icon">
                    <img src="https://img.icons8.com/?size=25&id=118490&format=png&color=ffffff" alt="Facebook">
                </a>
                <a href="https://x.com/i/flow/login?redirect_after_login=%2Fbldeacet" aria-label="Twitter" class="social-icon">
                    <img src="https://img.icons8.com/?size=25&id=de4vjQ6J061l&format=png&color=ffffff" alt="Twitter">
                </a>
                <a href="https://www.instagram.com/bldeacetmedia/?hl=en" aria-label="Instagram" class="social-icon">
                    <img src="https://img.icons8.com/?size=25&id=85154&format=png&color=ffffff" alt="Instagram">
                </a>
                <a href="https://www.youtube.com/@bldeacetmedia7621" aria-label="YouTube" class="social-icon">
                    <img src="https://img.icons8.com/?size=25&id=85375&format=png&color=ffffff" alt="YouTube">
                </a>
            </div>
        </div>

        <!-- Footer Right Section: Location & Map -->
        <div class="footer-right">
            <h3>Location</h3>
            <p><b>BLDEACET Central Library</b></p>
            <p>Vijayapura, Karnataka, India</p>
            <p><b>Contact:</b> 08352-261120</p>
            <p><b>Fax:</b> 08352-262945</p>
            <p><b>Email:</b> principal@bldeacet.ac.in</p>
            <p><b>Web:</b> <a href="http://bldeacet.ac.in" target="_blank">bldeacet.ac.in</a></p>

            <!-- Embed Google Map -->
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15274.215654423784!2d75.717493!3d16.848472000000005!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc6558982df81f7%3A0x589f36a4e9dc6fab!2sBLDEA&#39;s%20V%20P%20Dr%20PG%20Halakatti%20College%20of%20Engineering%20%26%20Technology!5e0!3m2!1sen!2sin!4v1729619144015!5m2!1sen!2sin" width="400" height="250" style="border:0;" allowfullscreen="" loading="lazy" title="Google Map showing BLDEACET Central Library location" alt="Google Map showing BLDEACET Central Library location"></iframe>
            </div>
        </div>
    </div>
</footer>

    <?php
}

// Render the Header
renderHeader();
?>

<!-- Library Name Below Header -->
<div class="library-name">
    <span>&#10042;</span> BLDEACET <span>Central Library</span> and Information Center<img src="Header.svg" alt="Library-image">
</div>

<!-- Counter Section -->
<section>
    <div class="counter-section">
        <div class="counter">
            <h3><span class="count" data-target="24204">0</span></h3>
            <hr>
            <p>Hard Copies <br> [No' by Volume : 91854] </p>
        </div>
        <div class="counter">
            <h3><span class="count" data-target="24352">0</span></h3>
            <hr>
            <p>E-Books <br> [No' by Volume : 24352] </p>
        </div>
        <div class="counter">
            <h3><span class="count" data-target="48556">0</span></h3>
            <hr>
            <p>Total <br> [No' by Volume : 116206] </p>
        </div>
    </div>    
</section>

<section>
    <div class="data">
        <center><p> <span><sub>*</sub></span> Data as on 23-11-2023 </p></center>
    </div>
</section>

<!-- Vision and Mission Section -->
<section class="vision-mission">
    <div class="content">
        <div class="text">
            <div class="section-box">
                <h3><span>&#10042;</span> Vision</h3>
                <hr>
                <p>Empowering Minds, Enriching Futures: A Leading Hub of Knowledge in Science & Technology.</p>
            </div>
            <div class="section-box">
                <h3><span>&#10042;</span> Mission</h3>
                <hr>
                <p>Unleashing the Power of Information: Providing Seamless Access to Comprehensive Resources for Teaching, Research & Learning.</p>
            </div>
        </div>
        <div class="eye-container">
            <div class="eye-background">
                <div class="eye">
                    <div class="pupil"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Library Timings Section -->
<section class="timings">
    <h3><span>&#10042;</span> Library Timings </h3>
    <table>
        <tr>
            <td>Books issue/return on weekdays</td>
            <td>09:00 AM to 05:15 PM</td>
        </tr>
        <tr>
            <td>During Examination</td>
            <td>08:00 AM to 12:00 Midnight</td>
        </tr>
    </table>
</section>

<!-- Back to Top Button -->
<button id="backToTopBtn" title="Go to top"><b>&#8593;</b></button>

<!-- Chatbot Button and Sidebar -->
<div class="chatbot-container">
    <button id="chatbotButton">
        <img src="assistant.svg" alt="Assistant Icon">
    </button>
    <div id="chatSidebar" class="chat-sidebar">
        <div class="chat-header">
        <h3 id="chatHeaderMessage"></h3>
            <button id="closeChat">&times;</button>
        </div>
        <div class="chat-body">
        <div class="chat-footer">
        <p class="chat-note">Hi Learner, I'm here to help, I may not be perfect, but I promise I will try my best for you!</p>
        </div>
            <div class="messages"></div>
            <input type="text" id="chatInput" placeholder="Ask Something ...">
            <button id="sendMessage">Send</button>
        </div>
    </div>
</div>

<!-- Render the Footer -->
<?php renderFooter(); ?>

<script src="script.js?v=6.0"></script>
</body>
</html>
