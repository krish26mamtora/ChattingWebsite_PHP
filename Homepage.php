<?php
if (file_exists('partials/nav.php')) {
    include 'partials/nav.php';
} else {
    echo "Navigation file not found.";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatSite - Connect & Chat</title>
    <link rel="stylesheet" href="styles_Homepage.css">
    <script defer src="script_Homepage.js"></script>
</head>

<body>

    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to WhisperWeb</h1>
            <p>Your favorite place to chat and connect with friends.</p>
            <a href="Userloginpage.php" class="cta">Get Started</a>
        </div>
    </section>

    <section class="features" id="features">
        <h2>Features</h2>
        <div class="feature-list">
            <div class="feature">
                <i class="fa fa-comments" aria-hidden="true"></i>
                <h3>Real-Time Messaging</h3>
                <p>Chat with your friends instantly, anytime.</p>
            </div>

            <div class="feature">
                <i class="fa fa-users" aria-hidden="true"></i>
                <h3>Community Engagement</h3>
                <p>Connect with like-minded individuals and form meaningful relationships.</p>
            </div>

        </div>
    </section>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 ChatSite. All rights reserved.</p>
            <ul>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </footer>
</body>

</html>