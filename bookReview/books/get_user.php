<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch logged-in user (example: using a session)
session_start();
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session after login

// Directly execute the query
$sql = "SELECT username FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the result as an associative array
    $row = $result->fetch_assoc();
    $username = $row['username'];
} else {
    $username = null; // or handle the case where no user is found
}

echo json_encode(['username' => $username]);

$conn->close();
?>