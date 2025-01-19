<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrow System</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <div class="process">
        <?php
            include 'dbConnection.php'; // Include the database connection file

            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {

                if (isset($_POST['borrow_book']))
                {
                    // Retrieve form data
                    $studentName =  filter_var($_POST['userName'], FILTER_SANITIZE_STRING);
                    $studentID = filter_var($_POST['userID'], FILTER_SANITIZE_STRING);
                    $bookID = filter_var($_POST['books'], FILTER_SANITIZE_STRING);
                    $borrowDate = $_POST['borrowDate'];
                    $token = $_POST['token'];
                    $returnDate = $_POST['returnDate'];
                    $fees = $_POST['fees'];
                    $paid = $_POST['paid'];

                    $date1 = strtotime($borrowDate); 
                    $date2 = strtotime($returnDate);


                    if (file_exists("./token.json")) 
                    {
                        $jsonData = json_decode(file_get_contents("./token.json"), true);

                        if (isset($jsonData[0]['token'])) 
                        {
                            $inputToken = (int)$_POST['token']; 
                            $tokens = $jsonData[0]['token'];    

                            if (in_array($inputToken, $tokens)) 
                                $flagToken = 1;
                            else 
                                $flagToken = 0;
                        } 
                        if (isset($jsonData[0]['usedToken']) && $flagToken == 1)
                            $flagUsedToken = 0;

                            if (isset($jsonData[0]['usedToken'])) 
                            {
                                $inputToken = (int)$_POST['token']; 
                                $tokens = $jsonData[0]['usedToken'];    
            
                                if (in_array($inputToken, $tokens)) 
                                    $flagUsedToken = 1;
                                else 
                                    $flagUsedToken = 0;
                            } 
                            else 
                                $flagUsedToken = -1;
                    } 

                    if ($date1 == $date2)
                    {
                        echo "You're not allow to return in same date";
                        return;
                    }

                    if ($date1 > $date2)
                    {
                        echo "Invalid return date";
                        return;
                    }

                    if ($flagUsedToken != 0)
                    {
                        echo "You are not allow to use this token because its already used";
                        return;
                    }

                    // 1 day = 86400 seconds
                    if (($date2 - $date1) > 10 * 86400 && $flagToken != 1) 
                    {
                        echo "<div class ='message error'>You're not allowed to loan the book for more than 10 days. Days: " . (($date2 - $date1) / 86400)." </div>";
                        return;
                    }
                    


                    // Validation
                    // User name
                    if (!preg_match("/[A-Za-z\-]/", $_POST['userName'])) 
                    {
                        echo "<div class='message error'>Invalid Name</div>";
                        return;
                    }
                    // Student Name
                    if (!preg_match("/\d{2}-\d{5}-\d{1}/", $studentID)) 
                    {
                        echo "<div class='message error'>Invalid UserID</div>";
                        return;
                    }
                    // Fee
                    if (!preg_match("/[0-9]/", $_POST['fees'])) 
                    {
                        echo "<div class='message error'>Invalid Fees</div>";
                        return;
                    }
                    // Selecct book
                    if ($bookID == "")
                    {
                        echo "<div class='message error'>Please select a book</div>";
                        return;
                    }
                    
                    $bookTitle = "";
                    $sql = "SELECT bookTitle, bookQuantity FROM book WHERE id = '$bookID'";
                    $result = mysqli_query($conn, $sql);
                    if ($result && mysqli_num_rows($result) > 0) 
                    {
                        $row = mysqli_fetch_assoc($result);
                        $availableQuantity = $row['bookQuantity'];
                        $bookTitle = $row['bookTitle'];
                
                        if ($availableQuantity <= 0) 
                        {
                            echo "<div class='message error'>Book is not available.</div>";
                            return;
                        } 
                    }

                    $cookieName = str_replace(['=', ',', ';', ' ', "\t", "\r", "\n", "\013", "\014"], '_', $bookTitle); // Cookie name is the book title

                    if (isset($_COOKIE[$cookieName])) 
                    {
                        // Check if the cookie value matches the student name
                        if ($_COOKIE[$cookieName] == $studentName) 
                        {
                            echo "<div class='message error'>You're not allowed to loadn the same book again.</div>";
                            return;
                        }
                    }

                    // Set cookie {Name: book title & value: student name
                    setcookie($cookieName, $studentName, time() + (10 * 24 * 60 * 60), "/"); // Cookie expires in 10 days

                    if (file_exists("./token.json") && $flagToken == 1) 
                    {
                        // Read and decode the JSON file
                        $jsonData = json_decode(file_get_contents("./token.json"), true) ?: [];
                        
                        if (!isset($jsonData[0]['usedToken']) || !is_array($jsonData[0]['usedToken'])) 
                            $jsonData[0]['usedToken'] = [];
                        
                    
                        // Add the token if it doesn't exist
                        if (!in_array($token, $jsonData[0]['usedToken'])) 
                            $jsonData[0]['usedToken'][] = $token; // Push the new used token
                        
                    
                        // Save the updated JSON back to the file
                        file_put_contents("./token.json", json_encode($jsonData, JSON_PRETTY_PRINT));
                    }
                

                    // Decrement the quantity in the database by 1
                    $newQuantity = $availableQuantity - 1;
                    $updateSQL = "UPDATE book SET bookQuantity = '$newQuantity' WHERE id = '$bookID'";
                    if (!mysqli_query($conn, $updateSQL)) 
                    {
                        echo "Error updating book quantity.";
                        return;                  
                    }
                

                    // Display the submitted data
                    echo "<div class='details'>";
                    echo "<h2>Details:</h2>";
                    echo "<p><strong>Student Full Name:</strong> " . $studentName . "</p>";
                    echo "<p><strong>Student ID:</strong> " . $studentID . "</p>";
                    echo "<p><strong>Book Title:</strong> " . $bookTitle . "</p>";
                    echo "<p><strong>Borrow Date:</strong> " . $borrowDate . "</p>";
                    echo "<p><strong>Token:</strong> " . $token . "</p>";
                    echo "<p><strong>Return Date:</strong> " . $returnDate . "</p>";
                    echo "<p><strong>Fees:</strong> $" . $fees . "</p>";
                    echo "</div>";
                    return;
                }


                // Book upload in database
                else if (isset($_POST['new_book']) || isset($_POST['book_update']) || isset($_POST['book_delete']))
                {
                    $bookTitle = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
                    $bookAuthor = filter_var($_POST['authorName'], FILTER_SANITIZE_STRING);
                    $isbn = filter_var($_POST['isbn'], FILTER_SANITIZE_STRING);
                    $bookCategory = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
                    $bookQuantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);

                    // Validation
                    // Book Title
                    if (!preg_match("/[A-Za-z\-]/", $bookTitle)) 
                    {
                        echo "<div class='message error'>Invalid Book Title</div>";
                        return;
                    }
                    // Book Author
                    if (!preg_match("/[A-Za-z\-]/", $bookAuthor)) 
                    {
                        echo "<div class='message error'>Invalid Book Author</div>";
                        return;
                    }
                    // Book Category
                    if (!preg_match("/[A-Za-z\-]/", $bookCategory)) 
                    {
                        echo "<div class='message error'>Invalid Book Category</div>";
                        return;
                    }
                    // ISBN
                    if (!preg_match("/[0-9]/", $isbn) || strlen($isbn) != 13)
                    {
                        echo "<div class='message error'>Invalid ISBN</div>";
                        return;
                    }
                    // Book Quantity
                    if (!preg_match("/[0-9]/", $bookQuantity)) 
                    {
                        echo "<div class='message error'>Invalid Book Quantity</div>";
                        return;
                    }
                    // Book Quantity must be greater than 0
                    if ($bookQuantity < 1) 
                    {
                        echo "<div class='message error'>Book Quantity must be greater than 0</div>";
                        return;
                    }


                    $sql = "";

                    // Insert new book into database
                    if (isset($_POST['new_book']))
                    {
                        // Check if the book already exists in the database
                        $sql = "SELECT * FROM book WHERE bookTitle='$bookTitle' AND bookAuthor='$bookAuthor' AND isbn='$isbn' AND bookCategory='$bookCategory'";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) 
                        {
                            echo "<div class='message error'>Book already exists in the database.</div>";
                            return;
                        }

                        // Insert the book into the database
                        $sql = "INSERT INTO book (bookTitle, bookAuthor, isbn, bookCategory, bookQuantity) VALUES ('$bookTitle', '$bookAuthor', '$isbn', '$bookCategory', '$bookQuantity')";
                        if (mysqli_query($conn, $sql)) 
                        {
                            echo "<div class='message success'>New book added successfully.</div>";
                            return;
                        } 
                    }

                    // update book in database
                    else if (isset($_POST['book_update'])) 
                    {
                        $bookID = filter_var($_POST['book_id'], FILTER_SANITIZE_NUMBER_INT);
                        $sql = "UPDATE book SET bookTitle='$bookTitle', bookAuthor='$bookAuthor', isbn='$isbn', bookCategory='$bookCategory', bookQuantity='$bookQuantity' WHERE id='$bookID'";
                        if (mysqli_query($conn, $sql)) 
                        {
                            echo "<div class='message success'>Book updated successfully.</div>";
                            return;
                        } 
                    }

                    // Delete book from database
                    else if (isset($_POST['book_delete'])) 
                    {
                        $bookID = filter_var($_POST['book_id'], FILTER_SANITIZE_NUMBER_INT);
                        $sql = "DELETE FROM book WHERE id='$bookID'";
                        if (mysqli_query($conn, $sql)) 
                        {
                            echo "<div class='message success'>Book deleted successfully.</div>";
                            return;
                        } 
                    }
                    
                    // Display an error message if the query fails
                    echo "<div class='message error'>Error: " . $sql . "<br>" . mysqli_error($conn) . "</div>";
                }
                echo "<div class='message error'>No data submitted.</div>";
            }  
        ?>
    </div>        
</body>
</html>