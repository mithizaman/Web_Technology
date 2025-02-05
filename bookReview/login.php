<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'user_auth';
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

// Create a connection
$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Set error mode to warning

// Check for connection errors
if (!$conn) {
    die("Connection failed: " . $conn->errorInfo()[2]);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Fetch user from database
$email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitize email input
$sql = "SELECT * FROM users WHERE email = '$email'"; // Unsafe for user inputs
$user = $conn->query($sql)->fetch(PDO::FETCH_ASSOC);

// Verify user
if ($user && password_verify($password, $user['password'])) {
    // Login successful
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // Redirect to dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Login failed
    echo "Invalid email or password!";
}
?>