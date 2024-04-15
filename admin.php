<?php

/*******w******** 
    
    Name:George Monday
    Date:27/01/2024
    Description:

****************/
require('connect.php');
require('authenticate.php');

// Initialize variables
$userLoggedIn = false;
$usernameFromDatabase = '';
$userRole = '';

try {
    // Check if the user is logged in
    if (isset($_SESSION['id'])) {
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
        } else {
            // User not found in database
            echo "Error: User not found in the database.";
        }
    }
} catch (PDOException $e) {
    // Error occurred while fetching user information
    echo "Error: " . $e->getMessage();
}

// Retrieve all data from the database
$query = "SELECT * FROM nbaeliteroster";
$statement = $db->query($query);
$players = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA Elite Roster - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('nav_admin.php'); ?>
    <div class="container mt-5">
            <div id="header">
                <h2>Page Administration</h2>
            </div>
            <?php if ($players) : ?>
                <!-- Display the data as a list -->
                <ul class="list-group">
                    <?php foreach ($players as $player) : ?>
                        <!-- Display player name as a list item -->
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <!-- Player name as a link to post.php page -->
                            <a href="post.php?player_id=<?= isset($player['player_id']) ? $player['player_id'] : 'N/A' ?>">
                                <?= isset($player['player_name']) ? $player['player_name'] : 'N/A' ?>
                            </a>
                        </li>
                        <p></p>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?> 
                <!-- Display a message if there are no players -->
                <div class="alert alert-warning mt-4" role="alert">
                    No players found.
                </div>
            <?php endif; ?>

        <footer id="footer" class="mt-5">
            <p class="text-center">Copyright 2024 - All Rights Reserved.</p>
        </footer>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

