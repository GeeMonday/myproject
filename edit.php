<?php

/*******w******** 
    
    Name: George Monday
    Date: 27/03/2024
    Description: Updated code to retrieve and edit data from the nbaeliteroster table.

****************/

require('connect.php'); 
require('authenticate.php');

function getPlayerById($db, $player_id) {
    $query = "SELECT * FROM nbaeliteroster WHERE player_id = :player_id"; // Updated to use 'player_id'
    $statement = $db->prepare($query);
    $statement->bindParam(':player_id', $player_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

// Check if the player ID is provided in the URL
if (isset($_GET['player_id'])) {
    $player_id = $_GET['player_id'];

    // Retrieve the player by ID
    $player = getPlayerById($db, $player_id);

    // If the player exists, proceed with handling form submissions
    if ($player) {
        // Check if the form is submitted for updating the player data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            // Validate and sanitize the form data
            $player_name = filter_input(INPUT_POST, 'player_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $team = filter_input(INPUT_POST, 'team', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $skill_rating = filter_input(INPUT_POST, 'skill_rating', FILTER_SANITIZE_NUMBER_INT);

            // Update the player data in the database
            $query = "UPDATE nbaeliteroster SET player_name = :player_name, team = :team, position = :position, skill_rating = :skill_rating WHERE player_id = :player_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':player_id', $player_id);
            $statement->bindValue(':player_name', $player_name);
            $statement->bindValue(':team', $team);
            $statement->bindValue(':position', $position);
            $statement->bindValue(':skill_rating', $skill_rating);

            if ($statement->execute()) {
                // Redirect to the homepage or display a success message
                header('Location: players.php');
                exit;
            } else {
                // Handle the case where the update fails
                echo "Error: Unable to update the player data.";
            }
        }
    } else {
        // Handle the case where the player with the specified ID does not exist
        echo "Player not found.";
    }
} else {
    // Handle the case where the player ID is not provided in the URL
    echo "Player ID not specified.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Edit Player Data</title>
    <!-- Include TinyMCE script -->
    <script src="https://cdn.tiny.cloud/1/91x1238q9wh2ldf8mgvhy4pnp6xabrihkrrjse0ij3k3whxu/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        // Initialize TinyMCE on the div element
        tinymce.init({
            selector: '#player_description', // Use the ID of the div element
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        });
    </script>
</head>
<?php include('nav.php'); ?>
<body>
    <div id='main-content'>
        <div id='header'>
            <h2><a href='players.php'>Edit Player</a></h2>
        </div> 
        <!-- HTML form for editing player data -->
        <form action="edit.php?player_id=<?php echo $player_id ?>" method="post">
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
            <h3>Comments</h3>
            <!-- Use a div element instead of a textarea -->
            <div id="player_description" style="height: 200px;"></div>
            <br>
            <input type="submit" name="update" value="Update">
        </form>
    </div>
</body>
</html>