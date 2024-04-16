<?php

/*******w******** 
    
    Name:George Monday
    Date:27/01/2024
    Description:

****************/
require('connect.php');

// Function to get a player name by its ID
function getPlayerById($db, $player_id) {
    $query = "SELECT * FROM nbaeliteroster WHERE player_id = :player_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':player_id', $player_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

?>
<?php include('nav_admin.php'); ?>
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
        // Check if image URLs are provided and not empty
        
        echo "<h2>{$selectedPlayer['player_name']}</h2>";
        echo "<p><strong>Team:</strong> {$selectedPlayer['team']}</p>";
        echo "<p><strong>Position:</strong> {$selectedPlayer['position']}</p>";
        echo "<p><strong>Skill Rating:</strong> {$selectedPlayer['skill_rating']}</p>"; 

         // Display the player image if available
         if (!empty($selectedPlayer['player_image'])) {
            echo "<img src='{$selectedPlayer['player_image']}' alt='{$selectedPlayer['player_name']}'>";
        } else {
            echo "<p>No image available</p>";
        }
        /*
        // Retrieve the player description from the comments table
        $query = "SELECT comment FROM comments WHERE comment_id = :comment_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':comment_id', $comment_id);
        $statement->execute();
        $comment_result = $statement->fetch(PDO::FETCH_ASSOC);

        // Display the player description
        if ($comment_result) {
            echo "<h4>Interesting Facts</h4>";
            echo "<p>{$comment_result['comment']}</p>";
        } else {
            echo "<p>No interesting facts available.</p>";
        }
        */

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
