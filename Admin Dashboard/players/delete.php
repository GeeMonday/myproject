<?php
/*******w******** 
    
    Name: George Monday
    Date: 27/03/2024
    Description: Updated code to retrieve and edit data from the nbaeliteroster table.

****************/
require('connect.php'); 
require ('authenticate.php');
function getPlayerById($db, $player_id) {
    $query = "SELECT * FROM nbaeliteroster WHERE player_id = :player_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':player_id', $player_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['player_id'])) { 
    $player_id = $_GET['player_id'];
    $player = getPlayerById($db, $player_id);

    if ($player) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            // Update player data
             // Validate and sanitize the form data
             $player_id = filter_input(INPUT_POST, 'player_id', FILTER_SANITIZE_NUMBER_INT);
             $player_name = filter_input(INPUT_POST, 'player_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
             $team = filter_input(INPUT_POST, 'team', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
             $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
             $skill_rating = filter_input(INPUT_POST, 'skill_rating', FILTER_SANITIZE_NUMBER_INT);
 
            //Update the elite roster table in the database
            $query = "UPDATE nbaeliteroster SET player_name = :player_name, team = :team, position = :position, skill_rating = :skill_rating WHERE player_id = :player_id";
            $statement = $db->prepare($query);
            // Bind paramaeters
            $statement->bindValue(':player_id', $player_id);
            $statement->bindValue(':player_name', $player_name);
            $statement->bindValue(':team', $team);
            $statement->bindValue(':position', $position);
            $statement->bindValue(':skill_rating', $skill_rating);


            if ($statement->execute()) {
                header('Location: index.php');
                exit;
            } else {
                echo "Error: Unable to update the elite roster table.";
            }
        }

        elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
            // Delete player record
            $query = "DELETE FROM nbaeliteroster WHERE player_id = :player_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':player_id', $player_id);

            if ($statement->execute()) {
                header('Location: admin.php');
                exit;
            } else {
                echo "Error: Unable to delete the player record.";
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Edit Elite Roster Table</title>
</head>
<?php include('nav_admin.php'); ?>
<body>
    <div id='main-content'>
        <div id='header'>
            <h2><a href='players.php'> Delete player</a></h2>
        </div>

        <form action="delete.php?player_id=<?php echo $player_id ?>" method="post"> 
            <label for="player_name">Player Name:</label>
            <input type="text" id="player_name" name="player_name" value="<?php echo isset($player['player_name']) ? $player['player_name'] : '' ?>" required>
            <br>
            <label for="team">Team:</label>
            <input type="text" id="team" name="team" value="<?php echo isset($player['team']) ? $player['team'] : '' ?>" required>
            <br>
            <label for="position">Position:</label>
            <input type="text" id="position" name="position" value="<?php echo isset($player['position']) ? $player['position'] : '' ?>" required>
            <br>
            <label for="skill_rating">Skill Rating:</label>
            <input type="text" id="skill_rating" name="skill_rating" value="<?php echo isset($player['skill_rating']) ? $player['skill_rating'] : '' ?>" required>
            <br>
            <!-- Add delete button -->
            <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this player?');">
        </form>
    </div>
</body>
</html>
<?php
    } else {
        // Handle the case where the player with the specified ID does not exist
        echo "Player not found.";
    }
} else {
    // Handle the case where the player ID is not provided in the URL
    echo "Player ID not specified.";
}
?>

        
