<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username']; // Get the username from the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Authors</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <!-- Header Section -->
    <header class="dashboard-header">
        <div class="header-content">
            <span class="username">Welcome, <?php echo htmlspecialchars($username); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <!-- Banner -->
    <div class="banner">
        <div class="banner-content">
            <img src="images/dashboard.jpg" alt="banner" class="logo">
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <h2>Hello!</h2>
        <p>"Here is a list of book authors. Please select your preferred author and click the "See More" button to explore your desired book."</p>
        <ul class="author-list">
            <div class="author-cards">
                <div class="author-card">
                    <img src="images/jk.jpg" alt="book">
                    <li><a href="books/author1.php">J.K. Rowling</a></li>
                    <button><a href="books/author1.php">See More</a></button>
                </div>
                <div class="author-card">
                    <img src="images/george.jpg" alt="book">
                    <li><a href="books/author2.php">George R.R. Martin</a></li>
                    <button><a href="books/author2.php">See More</a></button>
                </div>
                <div class="author-card">
                    <img src="images/Agatha_Christie.png" alt="book">
                    <li><a href="books/author3.php">Agatha Christie</a></li>
                    <button><a href="books/author3.php">See More</a></button>
                </div>
            </div>
        </ul>
    </div>

    <!-- footer -->
    <footer class="footer">
        <p>&copy; 2025 Book Review App. All rights reserved.</p>
    </footer>
</body>
</html>