<?php

// Check if the user is logged in
if (isset($_SESSION['id'])) {
    try {
        // Include the database connection file
        require('connect.php');

        // Retrieve user information from the database
        $query = "SELECT username, role FROM users WHERE id = :id"; // Separate columns with a comma
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
            var_dump($isAdmin); 
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

// If the user is not logged in, set a default value for usernameFromDatabase
if (!$userLoggedIn) {
    $usernameFromDatabase = 'User Profile'; // Display a default message
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA Elite Rooster</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS for left-aligned navigation */
        .nav-vertical {
            flex-direction: column;
        }
        .navbar-collapse {
            justify-content: flex-start;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">NBA Elite Rooster</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="category.php">Category</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="players.php">Players</a>
            </li>
<?php if ($userLoggedIn) { ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php echo htmlspecialchars($usernameFromDatabase); ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="profile.php">Profile</a> <!-- Assuming profile.php is the user profile page -->
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="logout.php">Log out</a>
        </div>
    </li>
<?php } else { ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Log in
        </a>
        <div class="dropdown-menu" aria-labelledby="loginDropdown">
            <a class="dropdown-item" href="login.php">Registered user</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="create_user.php">Create Profile</a>
        </div>
    </li>
<?php } ?>
<!-- Add conditional display for the Admin link based on user role -->
<?php if ($userLoggedIn) { ?>
    <li class="nav-item">
        <a class="nav-link" href="admin.php">Admin</a>
    </li>
<?php } ?>
<li class="nav-item">
    <a class="nav-link" href="about_us.php">About us</a>
</li>
    </ul>
</div>
</nav>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>
