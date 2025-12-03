<?php
session_start();
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName  = trim($_POST['lastName'] ?? '');
    $nickName  = trim($_POST['nickName'] ?? '');
    $password  = $_POST['password'] ?? '';

    // Basic validation
    if ($firstName === '' || $lastName === '' || $password === '') {
        $error = "First name, last name, and password are required.";
    } else {
        // Generate salt and hashed password
        $salt = uniqid(mt_rand(), true);                      // random salt
        $hash = hash('sha256', $password . $salt);            // salted hash

        // Insert into users table
        $stmt = $pdo->prepare("
            INSERT INTO users (firstName, lastName, nickName, passwordSalt, passwordHash)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$firstName, $lastName, $nickName, $salt, $hash]);

        // Auto-login the new user
        $newUserId = $pdo->lastInsertId();
        $_SESSION['userId']    = $newUserId;
        $_SESSION['firstName'] = $firstName;

        echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container auth-container">
        <h1>✅ Success!</h1>
        <div class="success">
            <h2>Account created successfully!</h2>
        </div>
        <div class="user-id-display">
            <p>Your User ID is:</p>
            <strong>' . $newUserId . '</strong>
            <p style="margin-top: 10px; font-size: 0.9em;">⚠️ Save this number - you will need it to log in later!</p>
        </div>
        <div style="text-align: center;">
            <a href="index.php" class="btn">Continue to Home</a>
        </div>
    </div>
</body>
</html>';
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Quiz 2</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container auth-container">
        <h1>📝 Register</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="post" action="register.php">
            <label>
                First Name:
                <input type="text" name="firstName" required placeholder="Enter your first name">
            </label>
            
            <label>
                Last Name:
                <input type="text" name="lastName" required placeholder="Enter your last name">
            </label>
            
            <label>
                Nickname:
                <input type="text" name="nickName" placeholder="Enter a nickname (optional)">
            </label>
            
            <label>
                Password:
                <input type="password" name="password" required placeholder="Create a password">
            </label>
            
            <button type="submit">Create Account</button>
        </form>
        
        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Back to login</a></p>
        </div>
    </div>
</body>
</html>
