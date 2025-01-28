<?php require 'dbConnection.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book Store</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <img src="id.png">
    <div class="container">
        <!-- Top 1 boxes -->
        <div class="box1">
            <h2>Book Information</h2>
                <?php
                // Assuming $conn is your active MySQL connection
                $sql = "SELECT * FROM book"; // Correct SQL query
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) 
                {
                    echo '<table border="1" style="width:100%; text-align:left; border-collapse:collapse;">';
                    echo '<tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Category</th>
                            <th>Quantity</th>
                        </tr>'; // Table headers

                    while ($row = mysqli_fetch_assoc($result)) 
                    {
                        echo '<tr>';
                        echo '<td>' . $row['id'] . '</td>';
                        echo '<td>' . $row['bookTitle'] . '</td>';
                        echo '<td>' . $row['bookAuthor'] . '</td>';
                        echo '<td>' . $row['isbn'] . '</td>';
                        echo '<td>' . $row['bookCategory'] . '</td>';
                        echo '<td>' . $row['bookQuantity'] . '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                } 
                else 
                    echo "No books found in the database.";
                

               // mysqli_close($conn); // Close the database connection if you're done
                ?>
            </p>
        </div>
        <div class="box2">
        <h2>Data Modification Interface</h2>

    <?php
    // Fetch all book information from the database
    $sql = "SELECT * FROM book";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Book ID</th><th>Title</th><th>Author</th><th>ISBN</th><th>Category</th><th>Quantity</th><th>Actions</th></tr>";
        while ($book = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $book['id'] . "</td>
                    <td>" . $book['bookTitle'] . "</td>
                    <td>" . $book['bookAuthor'] . "</td>
                    <td>" . $book['isbn'] . "</td>
                    <td>" . $book['bookCategory'] . "</td>
                    <td>" . $book['bookQuantity'] . "</td>
                    <td>
                        <a href='?edit=" . $book['id'] . "'>Update</a> | 
                        <a href='?delete=" . $book['id'] . "' style='color:red;'>Delete</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No books available.</p>";
    }

    // Update Book Functionality
    if (isset($_GET['edit'])) {
        $bookId = $_GET['edit'];
        $sql = "SELECT * FROM book WHERE id = '$bookId'";
        $result = mysqli_query($conn, $sql);
        $book = mysqli_fetch_assoc($result);

        if ($book) {
            // Show update form
            echo '<h3>Update Book Information</h3>';
            echo '<form method="POST" action="">';
            echo '<input type="hidden" name="book_id" value="' . $book['id'] . '">';
            echo '<label for="title">Book Title:</label>';
            echo '<input type="text" id="title" name="title" value="' . $book['bookTitle'] . '" required><br><br>';
            echo '<label for="author">Book Author:</label>';
            echo '<input type="text" id="author" name="author" value="' . $book['bookAuthor'] . '" required><br><br>';
            echo '<label for="isbn">ISBN:</label>';
            echo '<input type="text" id="isbn" name="isbn" value="' . $book['isbn'] . '" required><br><br>';
            echo '<label for="category">Category:</label>';
            echo '<input type="text" id="category" name="category" value="' . $book['bookCategory'] . '" required><br><br>';
            echo '<label for="quantity">Quantity:</label>';
            echo '<input type="number" id="quantity" name="quantity" value="' . $book['bookQuantity'] . '" required><br><br>';
            echo '<button type="submit" name="update">Update</button>';
            echo '</form>';
        }
    }

    // Handle Update
    if (isset($_POST['update'])) {
        $bookId = $_POST['book_id'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $category = $_POST['category'];
        $quantity = $_POST['quantity'];

        $updateSql = "UPDATE book SET bookTitle = '$title', bookAuthor = '$author', isbn = '$isbn', bookCategory = '$category', bookQuantity = '$quantity' WHERE id = '$bookId'";
        $updateResult = mysqli_query($conn, $updateSql);

        if ($updateResult) {
            echo '<p>Book information updated successfully!</p>';
            // Redirect to avoid resubmission on page reload
            header('Location: index.php');
        } else {
            echo '<p>Error updating book information. Please try again.</p>';
        }
    }

    // Handle Delete
    if (isset($_GET['delete'])) {
        $bookId = $_GET['delete'];
        $deleteSql = "DELETE FROM book WHERE id = '$bookId'";
        $deleteResult = mysqli_query($conn, $deleteSql);

        if ($deleteResult) {
            echo '<p>Book deleted successfully!</p>';
            // Redirect to avoid resubmission on page reload
            header('Location: index.php');
        } else {
            echo '<p>Error deleting book. Please try again.</p>';
        }
    }
    ?>
</div>


        <!-- First Box 3: Display all tokens -->
        <div class="box3">
        <p>All Tokens </p>
            <ul>
                <?php
                $tokenFile = "./token.json";
                if (file_exists($tokenFile)) {
                    $jsonData = json_decode(file_get_contents($tokenFile), true);

                    if (isset($jsonData[0]['token'])) 
                    {
                        foreach ($jsonData[0]['token'] as $token) 
                            echo "<li>Token: $token</li>";
                    } 
                    else 
                        echo "<li>No tokens found in the JSON file.</li>";
                    
                } 
                else 
                    echo "<li>JSON file not found.</li>";
                
                ?>
            </ul>
        </div>

        <!-- Second Box 3 -->
        <div class="box4">
        <p>Used Tokens</p>
            <ul>
                <?php
                if (file_exists($tokenFile)) {
                    $jsonData = json_decode(file_get_contents($tokenFile), true);

                    if (isset($jsonData[0]['usedToken'])) 
                    {
                        foreach ($jsonData[0]['usedToken'] as $token) 
                            echo "<li>Token: $token</li>";
                    } 
                    else 
                        echo "<li>No tokens found in the JSON file.</li>";
                    
                } 
                else 
                    echo "<li>JSON file not found.</li>";
                
                ?>
            </ul>
        </div>

        <!-- Form Section -->
        <div class="box5">
            <img src = "book1.png "> 
        </div>
        <div class="box6">
            <img src = "book2.png"> 
        </div>
        <div class="box7">
            <img src = "book3.png"> 
        </div>

        <!-- Borrow Book -->
        <div class="box8">
            <div class="form-container">
                <h2>Borrow a Book</h2>
                <form method="POST" action="process.php">
                    <label for="userName">Student Name:</label><br>
                    <input type="text" id="userName" name="userName"><br>

                    <label for="userID">Student ID:</label><br>
                    <input type="text" id="userID" name="userID"><br>

                    <!-- Book Selection Dropdown -->
                    <label for="books">Book Title:</label><br>
                    <select name="books" id="books" required>
                        <option value="" disabled selected>Select a book</option>
                       
                        <?php
                        include 'dbConnection.php'; // Include database connection
                        
                        // Query to get books with quantity > 0
                        $sql = "SELECT * FROM book WHERE bookQuantity > 0";
                        $result = mysqli_query($conn, $sql);
                        
                        if ($result && mysqli_num_rows($result) > 0) 
                        {
                            
                            while ($row = mysqli_fetch_assoc($result)) 
                                echo '<option value="' . $row['id'] . '">' . $row['bookTitle'] . '</option>';
                        } 
                        else 
                            echo '<option value="" disabled>No books available</option>';
                        ?>
                    </select>

                    <label for="borrowDate">Borrow Date:</label><br>
                    <input type="date" id="borrowDate" name="borrowDate"><br>

                    <label for="token">Token:</label><br>
                    <input type="text" id="token" name="token"><br>

                    <label for="returnDate">Return Date:</label><br>
                    <input type="date" id="returnDate" name="returnDate"><br>

                    <label for="fees">Fees:</label><br>
                    <input type="text" id="fees" name="fees"><br>

                    <label for="paid">Paid:</label><br>
                    <select id="paid" name="paid"><br>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    <br>

                    <input type="submit" name="borrow_book" value="Submit">
                </form>
            </div>
        </div>

        <!-- Add Book -->
        <div class="box9">
            <div class="form-container">
                <h2>Book Information</h2>
                <form action="process.php" method="post">
                    <label for="title">Book Title:</label><br>
                    <input type="text" id="title" name="title"><br>

                    <label for="authorName">Author Name:</label><br>
                    <input type="text" id="authorName" name="authorName"><br>

                    <label for="isbn">ISBN:</label><br>
                    <input type="number" id="isbn" name="isbn"><br>

                    <label>Category:</label><br>
                    <select name = "category" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="data structure">Data Structure</option>
                        <option value="algorithm">Algorithm</option>
                        <option value="OOP">OOP</option>
                        <option value="database">Database</option>
                        <option value="software engineering">Software Engineering</option>
                        <option value="web technologies">Web Technologies</option>
                    </select>
                    <br>

                    <label for="quantity">Number of Book:</label><br>
                    <input type="number" id="quantity" name="quantity"><br>

                    <input type="submit" name="new_book" value="Submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>