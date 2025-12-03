<?php
$host = '127.0.0.1';
$dbname = 'itws2110-fall2025-liuc17-quiz2';
$username = 'root';       // default XAMPP user
$password = '';           // default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                   $username,
                   $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
