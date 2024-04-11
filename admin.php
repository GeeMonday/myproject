<?php

/*******w******** 
    
    Name:George Monday
    Date:27/01/2024
    Description:

****************/
require('connect.php');
require('authenticate.php');

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
    <?php include('nav.php'); ?>
    <div class="container mt-5">
            <div id="header">
                <h2><a href="index.php">Page Administration</a></h2>
            </div>
            <!-- Button to create a new player -->
            <div class="mt-4 mb-4">
                <form action="create.php" method="get">
                    <button type="submit" class="btn btn-primary btn-lg btn-block"><strong>Create Player</strong></button>
                </form>
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
                            <!-- Edit and Delete buttons -->
                            <div class="btn-group" role="group">
                                <a href="edit.php?player_id=<?= $player['player_id'] ?>" class="btn btn-info">Edit</a>
                                <a href="delete.php?player_id=<?= $player['player_id'] ?>" class="btn btn-danger">Delete</a>
                            </div>
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

