<?php
// Database connection
$host = 'localhost';
$dbname = 'user_auth';
$user = 'root'; 
$pass = ''; 

// Create a connection
$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Set error mode to warnings

// Get form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$rePassword = $_POST['re-password'];

// Validate that passwords match
if ($password !== $rePassword) {
    die("Error: Passwords do not match. <a href='register.html'>Try again</a>.");
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert into database
$email = filter_var($email, FILTER_SANITIZE_EMAIL); 
$username = filter_var($username, FILTER_SANITIZE_STRING); 

$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
$result = $conn->exec($sql);

if ($result) {
    // Redirect to login page after successful registration
    header("Location: login.html");
    exit(); // Ensure script stops execution after redirection
} else {
    // Handle insert failure
    echo "Error: Registration failed.";
}
?>