<?php
session_start();
if (!isset($_SESSION['userId'])) {
    // Not logged in -> go to login page
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Quiz 2</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['firstName']); ?>!</h1>
            <p style="color: #666; font-size: 1.1em;">What would you like to do today?</p>
        </div>
        
        <ul class="nav-links">
            <li>
                <a href="project.php">
                    <div style="font-size: 2em; margin-bottom: 10px;">📂</div>
                    <div>Manage Projects</div>
                    <div style="font-size: 0.9em; margin-top: 5px; opacity: 0.8;">View and create projects</div>
                </a>
            </li>
        </ul>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="logout.php" class="back-link">Logout</a>
        </div>
    </div>
</body>
</html>
