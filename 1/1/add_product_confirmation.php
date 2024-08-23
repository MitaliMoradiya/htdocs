<?php
// Start session if not already started
// session_start();

// Include connection and functions if needed
require('connection.inc.php');
require('functions.inc.php');

// Check if the user is logged in
if(!isset($_SESSION['USER_ID'])){
    header('location:login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Added Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: inline-block;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #28a745;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product Added Successfully!</h1>
        <p>Thank you for adding your product. It has been submitted for review and will appear on the site once approved.</p>
        <p><a href="index.php">Return to Home Page</a></p>
    </div>
</body>
</html>
