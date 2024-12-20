<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Box Layout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        .borrowForm-details {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
        }

        strong {
            color: #333;
        }

        .message.error {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = $_POST['fullName'];
    $studentId = $_POST['studentId'];
    $bookTitle = $_POST['bookTitle'];
    $borrowDate = $_POST['borrowDate'];
    $token = $_POST['token'];
    $returnDate = $_POST['returnDate'];
    $fees = $_POST['fees'];
    $paid = $_POST['paid'];
    
    // Validation
    if (!preg_match("/[A-Za-z\-]/", $_POST['fullName'])) 
    {
        echo "<div class='message error'>Invalid Student Name</div>";
        return;
    }
    if (!preg_match("/\d{2}-\d{5}-\d{1}/", $_POST['studentId'])) 
    {
        echo "<div class='message error'>Invalid Student ID</div>";
        return;
    }
    if (!preg_match("/[0-9]/", $_POST['fees'])) 
    {
        echo "<div class='message error'>Invalid Fees</div>";
        return;
    }

    $cookieName = $bookTitle;


    if (!empty($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] === $fullName) {
        echo "You are not allowed to borrow the same book a second time.";
        return;
    }

   setcookie($cookieName, $fullName, time() + 300, "/"); // for 300seconds or 2minutes

    echo "You are allowed to borrow this book.";


    //Display the borrow book details
    echo '<div class="borrowForm-details">';
    echo "<h2>Form Details</h2>";
    echo "<p><strong>Student Full Name:</strong> " . $fullName . "</p>";
    echo "<p><strong>Student ID:</strong> " . $studentId . "</p>";
    echo "<p><strong>Book Title:</strong> " . $bookTitle . "</p>";
    echo "<p><strong>Borrow Date:</strong> " . $borrowDate . "</p>";
    echo "<p><strong>Token:</strong> " . $token . "</p>";
    echo "<p><strong>Return Date:</strong> " . $returnDate . "</p>";
    echo "<p><strong>Fees:</strong> $" . $fees . "</p>";
    echo '</div>';
    return;
}
echo "<div class='message error'>No data submitted.</div>";


?>

</body>
</html>



