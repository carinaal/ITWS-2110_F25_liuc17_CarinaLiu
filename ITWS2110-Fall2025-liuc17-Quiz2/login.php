<?php
session_start();
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Read form fields
    $userId   = trim($_POST['userId'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($userId === '' || $password === '') {
        $error = "User ID and password are required.";
    } else {
        // 2. Look up the user by userId
        $stmt = $pdo->prepare("SELECT * FROM users WHERE userId = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // 3. User does not exist -> send to register page
            header("Location: register.php");
            exit;
        } else {
            // 4. Verify password with salt + hash
            $salt = $user['passwordSalt'];
            $hash = hash('sha256', $password . $salt);

            if (hash_equals($user['passwordHash'], $hash)) {
                // 5. Successful login -> store identity in session
                $_SESSION['userId']    = $user['userId'];
                $_SESSION['firstName'] = $user['firstName'];

                // 6. Redirect to index.php
                header("Location: index.php");
                exit;
            } else {
                // 7. Wrong password -> stay on login page with error
                $error = "Incorrect password. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Quiz 2</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container auth-container">
        <h1>Login</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="post" action="login.php">
            <label>
                User ID:
                <input type="text" name="userId" required pattern="\d+" placeholder="Enter your user ID">
            </label>
            
            <label>
                Password:
                <input type="password" name="password" required placeholder="Enter your password">
            </label>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="auth-footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
