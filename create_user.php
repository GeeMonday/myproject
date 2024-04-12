<?php
// Start session
session_start();

// Include database connection
require('connect.php');

// Check if the user is logged in
if (isset($_SESSION['id'])) {
    try {
        // Retrieve user information from the database
        $query = "SELECT username, role FROM users WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindParam(':id', $_SESSION['id']);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // User is authenticated
            $userLoggedIn = true;
            $usernameFromDatabase = $user['username'];
            $userRole = $user['role'];

            // Check if user is admin
            $isAdmin = ($userRole === 'admin');
        } else {
            // User not found in database
            $userLoggedIn = false;
            $usernameFromDatabase = '';
            $isAdmin = false;
        }
    } catch (PDOException $e) {
        // Error occurred while fetching user information
        $userLoggedIn = false;
        $usernameFromDatabase = '';
        $isAdmin = false;
        echo "Error: " . $e->getMessage();
    }
} else {
    // User not logged in
    $userLoggedIn = false;
    $usernameFromDatabase = '';
    $isAdmin = false;
}

if ($_POST && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Do not sanitize password
    $confirmPassword = $_POST['confirmPassword'];

    // Validate password match
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Generate a random salt
    $salt = bin2hex(random_bytes(16)); // Generate a 16-byte (128-bit) salt

    // Concatenate password with salt
    $saltedPassword = $password . $salt;

    // Hash the salted password
    $hashedPassword = password_hash($saltedPassword, PASSWORD_DEFAULT);

    // Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO users (username, email, password, salt, role) VALUES (:username, :email, :password, :salt, 'admin')";
    $statement = $db->prepare($query);

    // Bind values to the parameters
    $statement->bindValue(':username', $username);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', $hashedPassword);
    $statement->bindValue(':salt', $salt);

    // Execute the INSERT.
    // execute() will check for possible SQL injection and remove if necessary
    if($statement->execute()){
        echo "User created successfully!";
    } else {
        echo "Failed to create user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User Profile</title>
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
        .btn-create {
            width: 100%;
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 5px;
        }
        .btn-create:hover {
            background-color: #218838;
            border-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
    <a href="index.php" class="text-decoration-none">
    <h2 class="text-center mb-4">Create User Profile</h2>
</a>
        <form action="create_user.php" method="post" id="userForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-create">Create User</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JavaScript -->
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
        document.getElementById("userForm").addEventListener("submit", function(event) {
            if (!validatePassword()) {
                event.preventDefault(); // Prevent form submission if passwords don't match
            }
        });
    </script>
</body>
</html>
