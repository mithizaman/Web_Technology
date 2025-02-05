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
    <title>Another Author - Book Details</title>
    <link rel="stylesheet" href="../css/book.css">
</head>
<body>
    <!-- Header Section -->
    <header class="dashboard-header">
        <div class="header-content">
            <span class="username">Welcome, <?php echo htmlspecialchars($username); ?></span>
            <div class="button-container">
                <a href="../dashboard.php" class="logout-btn">Back</a>
                <a href="../logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </header>

    <div class="book-container">
        <img src="../images/book2.jpg" alt="book" class="logo">
        <h2>George R.R. Martin</h2>
        <div class="book-details">
            <h3>The world of ice and fire</h3>
            <p><strong>Description:</strong> Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis quos assumenda, eius quis voluptatum, molestiae veniam esse consequuntur fugit itaque possimus officiis perspiciatis et ratione unde. Minima, aspernatur! Sunt illo, placeat doloribus sequi officiis assumenda corporis, modi non odit ab accusantium odio est porro veniam recusandae officia fuga facilis, nihil quae. Ratione laudantium doloremque nihil aut. Ratione similique corrupti deserunt cupiditate explicabo iusto expedita. Facilis consequatur commodi optio corrupti quidem dolorum fugiat illo tempora iure, accusantium unde accusamus architecto error eum atque minus aliquam magnam. Veniam quam ipsa, pariatur autem iste temporibus? Nostrum possimus neque facilis, beatae assumenda ut nobis?</p>
            <p><strong>Genre:</strong> Friction</p>
            <p><strong>Published:</strong> 2005</p>
        </div>
        <div class="review-section">
            <h3>Reviews</h3>
            <form id="review-form">
                <textarea id="review" placeholder="Write your review..." required></textarea>
                <button type="submit">Submit Review</button>
            </form>
            <div id="reviews-list">
                <!-- Reviews will be dynamically added here -->
            </div>
        </div>
        <div class="like-section">
            <button id="like-btn">❤️ Like</button>
            <span id="like-count">0</span> Likes
        </div>
        <div class="user-section">
            <h3>Logged-in User: <span id="logged-in-user"></span></h3>
        </div>
    </div>
    <!-- footer -->
    <footer class="footer">
        <p>&copy; 2025 Book Review App. All rights reserved.</p>
    </footer>
    <script>
        const authorKey = 'author2_reviews';

        // Fetch logged-in user's name from the server
        function fetchLoggedInUser() {
            fetch('get_user.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('logged-in-user').textContent = data.username;
                });
        }

        // Fetch logged-in user on page load
        fetchLoggedInUser();

        // Review Submission
        const reviewForm = document.getElementById('review-form');
        const reviewsList = document.getElementById('reviews-list');

        // Load reviews from localStorage
        function loadReviews() {
            const reviews = JSON.parse(localStorage.getItem(authorKey)) || [];
            reviewsList.innerHTML = ''; // Clear current reviews list
            reviews.forEach((review, index) => {
                const reviewItem = document.createElement('div');
                reviewItem.classList.add('review-item');

                const reviewUser = document.createElement('strong');
                reviewUser.textContent = review.username + ': ';

                const reviewText = document.createElement('span');
                reviewText.textContent = review.comment;

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.setAttribute('data-index', index);
                deleteButton.addEventListener('click', function() {
                    deleteReview(index);
                });

                reviewItem.appendChild(reviewUser);
                reviewItem.appendChild(reviewText);
                reviewItem.appendChild(deleteButton);
                reviewsList.appendChild(reviewItem);
            });
        }

        // Delete review
        function deleteReview(index) {
            let reviews = JSON.parse(localStorage.getItem(authorKey)) || [];
            reviews.splice(index, 1);
            localStorage.setItem(authorKey, JSON.stringify(reviews));
            loadReviews(); // Reload reviews list
        }

        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const review = document.getElementById('review').value;
            const loggedInUser = document.getElementById('logged-in-user').textContent;

            if (loggedInUser && review) {
                const reviews = JSON.parse(localStorage.getItem(authorKey)) || [];
                reviews.push({ username: loggedInUser, comment: review });
                localStorage.setItem(authorKey, JSON.stringify(reviews));

                // Clear input field and reload reviews
                document.getElementById('review').value = '';
                loadReviews();
            }
        });

        // Initialize reviews on page load
        loadReviews();

        // Like Button
        const likeBtn = document.getElementById('like-btn');
        const likeCount = document.getElementById('like-count');
        let likes = parseInt(localStorage.getItem('author2_likes')) || 0;

        likeCount.textContent = likes;

        likeBtn.addEventListener('click', function () {
            likes++;
            likeCount.textContent = likes;
            localStorage.setItem('author2_likes', likes); // Save the like count to localStorage
        });
    </script>
</body>
</html>
