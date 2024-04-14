<?php

/*******w******** 
    
    Name: George Monday
    Date: 27/03/2024
    Description: Updated code to retrieve and edit data from the nbaeliteroster table.

****************/

require('connect.php'); 
require ('authenticate.php');
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

// Function to get comment by player ID
function getCommentByPlayerId($db, $player_id) {
    $query = "SELECT * FROM comments WHERE player_id = :player_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':player_id', $player_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

// Update comment if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_comment'])) {
    $player_id = $_POST['player_id'];
    $updated_comment = filter_input(INPUT_POST, 'updated_comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // Update the comment in the database
    $query = "UPDATE comments SET comment = :updated_comment WHERE player_id = :player_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':player_id', $player_id);
    $statement->bindParam(':updated_comment', $updated_comment);
    
    if ($statement->execute()) {
        echo "<p>Comment updated successfully!</p>";
    } else {
        echo "<p>Failed to update comment.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Player Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
<?php include('nav_admin.php'); ?>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center"><a href='players.php'>Edit Player</a></h2>
                    </div>
                    <div class="card-body">
                        <!-- HTML form for editing player data -->
                        <form action="edit.php?player_id=<?php echo $player_id ?>" method="post">
                            <div class="form-group">
                                <label for="player_name">Player Name:</label>
                                <input type="text" id="player_name" name="player_name" class="form-control" value="<?php echo isset($player['player_name']) ? $player['player_name'] : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="team">Team:</label>
                                <input type="text" id="team" name="team" class="form-control" value="<?php echo isset($player['team']) ? $player['team'] : '' ?>" required>
                            </div>
                            <div class="form-group">
    <label for="position">Position:</label>
    <input type="text" id="position" name="position" class="form-control" value="<?php echo isset($player['position']) ? $player['position'] : '' ?>" readonly>
</div>
                            <div class="form-group">
                                <label for="skill_rating">Skill Rating:</label>
                                <input type="text" id="skill_rating" name="skill_rating" class="form-control" value="<?php echo isset($player['skill_rating']) ? $player['skill_rating'] : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="player_description"><h4>Interesting Facts</h4></label>
                                <!-- Use a textarea for interesting facts -->
                                <textarea class="form-control" id="player_description" name="player_description" rows="4"></textarea>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary btn-block">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
