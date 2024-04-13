<?php
require('connect.php');
try {
    // Database connection setup
    // Assuming $db is already established

    // Retrieve user information from the database
    $query = "SELECT username, role FROM users WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindParam(':id', $_SESSION['id']);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // User is authenticated
        $usernameFromDatabase = $user['username'];
        $userRole = $user['role'];
    } else {
        // User not found in database
        $usernameFromDatabase = '';
        $userRole = '';
        echo "Error: User not found in the database.";
    }
} catch (PDOException $e) {
    // Error occurred while fetching user information
    $usernameFromDatabase = '';
    $userRole = '';
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .navbar-nav.flex-column {
    padding-top: 20px; /* Optional: Adjust the top padding */
}

.navbar-nav.flex-column .nav-item {
    margin-bottom: 10px; /* Optional: Adjust the margin between items */
}
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Admin Dashboard\players\manage_players.php">Manage Players</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_comments.php">Manage Comments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create_category.php">Manage Categories</a>
                    </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo htmlspecialchars($usernameFromDatabase); ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="profile.php">Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Log out</a>
                        </div>
                    </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap JS and dependencies (optional) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>
