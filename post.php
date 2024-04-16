<?php

require('connect.php');

// Function to get comments for a player by player ID
function getCommentsByPlayerId($db, $player_id) {
    $query = "SELECT * FROM comments WHERE player_id = :player_id ORDER BY created_at DESC";
    $statement = $db->prepare($query);
    $statement->bindParam(':player_id', $player_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}


// Function to get a player name by its ID
function getPlayerById($db, $player_id) {
    $query = "SELECT * FROM nbaeliteroster WHERE player_id = :player_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':player_id', $player_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

?>
<?php include('nav.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type= text/css href="main.css">
    <title>My players!</title>
</head>
<body>
<?php
// Start the main content div
echo "<div id='main-content'>"; 

// Header section
echo "<div id='header'>";
echo "<h2><a href='players.php'>NBA Player Info</a></h2>";
echo "</div>";

// Check if the ID parameter is set in the URL
if (isset($_GET['player_id'])) {
    // Get the player ID from the URL parameter
    $player_id = $_GET['player_id'];

    // Retrieve the player by ID
    $selectedPlayer = getPlayerById($db, $player_id);

    // If the player is found, display its details
    if ($selectedPlayer) {
        echo "<div class='nba-roster'>";
        // Display player details
        // Check if image URLs are provided and not empty
        if (!empty($selectedPlayer['image_url'])) {
            // Split the comma-separated string into an array of image URLs
            $imageArray = explode(',', $selectedPlayer['image_url']);

            // Loop through each image filename
            foreach ($imageArray as $image) {
                // Replace backslashes with forward slashes in the image path
                $image = str_replace('\\', '/', trim($image));
                echo "<img class='player-image' src='{$image}' alt='{$selectedPlayer['player_name']}' />";
            }
        } else {
            // If no image URLs are provided, display a default image or message
            echo "<p>No images available</p>";
        }
        echo "<h2>{$selectedPlayer['player_name']}</h2>";
        echo "<p><strong>Team:</strong> {$selectedPlayer['team']}</p>";
        echo "<p><strong>Position:</strong> {$selectedPlayer['position']}</p>";
        echo "<p><strong>Skill Rating:</strong> {$selectedPlayer['skill_rating']}</p>"; 
        // Display comments related to the player
$comments = getCommentsByPlayerId($db, $player_id);
if ($comments) {
    echo "<h4>Interesting facts</h4>";
    // Display comments in reverse chronological order
    foreach (array_reverse($comments) as $comment) {
        echo "<div class='comment'>";
        echo "<p><strong>{$comment['name']}</strong> - {$comment['comment']}</p>";
        echo "</div>";
    }
} else {
    echo "<p>No interesting facts available.</p>";
}
        // Adding submit comment link
        echo "<a href='comment.php?player_id={$player_id}'>Add an interesting facts to this player</a>";

        echo "</div>";
    } else {
        // Display a message if no player is found
        echo "<p>No player found</p>";
    }
} else {
    // Display a message if no ID parameter is set
    echo "<p>No player ID specified</p>";
}

// Footer section
echo "<footer id='footer'>";
echo "<h3>Copyright 2024 - All Right Reserved.</h3>";
echo "</footer>";

// Close the main content div
echo "</div>";
?>
</body>
</html>
