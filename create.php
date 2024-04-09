<?php

/*******w******** 
    
    Name:George Monday
    Date:22/03/2024
    Descripion:

****************/

require('connect.php');
require('authenticate.php');
if ($_POST && !empty($_POST['player_id']) && !empty($_POST['player_name']) && !empty($_POST['team']) && !empty($_POST['position']) && !empty($_POST['skill_rating'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $player_id = filter_input(INPUT_POST, 'player_id', FILTER_SANITIZE_FULL_NUMBER_INT);
    $player_name = filter_input(INPUT_POST, 'player_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $team = filter_input(INPUT_POST, 'team', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $skill_rating = filter_input(INPUT_POST, 'skill_rating', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO nbaeliteroster ( player_id, player_name, team, position, skill_rating) VALUES (:player_id, :player_name, :team, :position, :skill_rating)";
    $statement = $db->prepare($query);
    
    // Bind values to the parameters
    $statement->bindValue(':player_id', $player_id);
    $statement->bindValue(':player_name', $player_name);
    $statement->bindValue(':team', $team);
    $statement->bindValue(':position', $position);
    $statement->bindValue(':skill_rating', $skill_rating);
    
    // Execute the INSERT.
    // execute() will check for possible SQL injection and remove if necessary
    if($statement->execute()){
        echo "success!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Player</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="main.css">
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
    <style>
        /* Custom CSS to reduce box sizing */
        .form-control {
            width: 70%; /* Adjust width as needed */
        }
    </style>
</head>
<body>
    <?php include('nav.php'); ?>
    <div class="container mt-5">
        <div id="main-content">
            <div class='header'>
                <h2><a href='index.php'> Create New Player</a></h2>
            </div>
            <div class="post">
                <h1>New Player</h1>
                <!-- HTML form for creating a new table to the Elite Rosters database -->
                <form action="players.php" method="post">
                    <div class="form-group">
                        <label for="player_id">Player ID:</label>
                        <input type="text" class="form-control" id="player_id" name="player_id" required>
                    </div>
                    <div class="form-group">
                        <label for="player_name">Player Name:</label>
                        <input type="text" class="form-control" id="player_name" name="player_name" required>
                    </div>
                    <div class="form-group">
                        <label for="team">Team:</label>
                        <input type="text" class="form-control" id="team" name="team" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Position:</label>
                        <input type="text" class="form-control" id="position" name="position" required>
                    </div>
                    <div class="form-group">
                        <label for="skill_rating">Skill Rating:</label>
                        <input type="text" class="form-control" id="skill_rating" name="skill_rating" required>
                    </div>
                    <h3> Comments </h3>  
                    <!-- Use a div element instead of a textarea -->
                    <div class="form-group">
                        <div id="player_description" style="height: 200px;"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
