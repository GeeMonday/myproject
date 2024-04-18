<?php
require('connect.php');
session_start(); // Start the session

if ($_POST && !empty($_POST['email']) && !empty($_POST['password'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Do not sanitize password
    
    // Retrieve user record from database
    $query = "SELECT * FROM users WHERE email = :email";
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Concatenate entered password with user's salt
        $saltedPassword = $password . $user['salt'];
        
        // Verify password against hashed password
        if (password_verify($saltedPassword, $user['password'])) {
            // Set session variables
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            
            // Redirect to dashboard or another page
            header("location: http://localhost/wd2/older%20files/ProjectOne/index.php");
            exit(); // Make sure to exit after redirection
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            margin-top: 100px;
            border-radius: 10px;
            background-color: #ffffff;
            padding: 40px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-login {
            width: 100%;
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 5px;
        }
        .btn-login:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Login</h2>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <p> don't have an account, <a href= "create_user.php">Sign up here!</a></p>
            <button type="submit" class="btn btn-login">Login</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JavaScript -->
    <!-- Update the script to directly select the form element -->
<script>
    // Function to check if passwords match
    function validatePassword() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirmPassword").value;
        if (password != confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
    }

    // Add event listener to form submission
    document.querySelector("form").addEventListener("submit", function(event) {
        if (!validatePassword()) {
            event.preventDefault(); // Prevent form submission if passwords don't match
        }
    });
</script>
</body>
</html>




