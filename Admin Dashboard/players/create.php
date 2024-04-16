<?php
require('connect.php');
require('authenticate.php');

// Check if a file was uploaded
if ($_FILES && isset($_FILES['player_image'])) {
    $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    $fileType = $_FILES['player_image']['type'];

    if (!in_array($fileType, $allowedTypes)) {
        echo "Error: Only PNG and JPEG/JPG files are allowed.";
        exit;
    }

    $uploadDir = 'Admin Dashboard\players\Images';
    $uploadFile = $uploadDir . basename($_FILES['player_image']['name']);

    if (move_uploaded_file($_FILES['player_image']['tmp_name'], $uploadFile)) {
        echo "File uploaded to: " . $uploadFile;
    } else {
        echo "Possible file upload attack!";
        exit; // Exit script if file upload fails
    }
} else {
    echo "No file uploaded!";
    
}

// Proceed with database insertion only if file upload was successful
if ($_POST && !empty($_POST['player_name']) && !empty($_POST['team']) && !empty($_POST['position']) && !empty($_POST['skill_rating'])) {
    $player_name = filter_input(INPUT_POST, 'player_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $team = filter_input(INPUT_POST, 'team', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $skill_rating = filter_input(INPUT_POST, 'skill_rating', FILTER_SANITIZE_NUMBER_INT);
    $player_image = isset($uploadFile) ? $uploadFile : null; // Use the uploaded file path if available

    $query = "INSERT INTO nbaeliteroster (player_name, team, position, skill_rating, player_image) VALUES (:player_name, :team, :position, :skill_rating, :player_image)";
    $statement = $db->prepare($query);
    $statement->bindValue(':player_name', $player_name);
    $statement->bindValue(':team', $team);
    $statement->bindValue(':position', $position);
    $statement->bindValue(':skill_rating', $skill_rating);
    $statement->bindValue(':player_image', $player_image);

    if ($statement->execute()) {
        $successMessage = "Player created successfully!";
        
        // Check if the user is logged in
        if ($userLoggedIn) {
            // Get the user's ID from the session
            $user = $_SESSION['id'];

            // Save the comment in the users table
            $player_description = filter_input(INPUT_POST, 'player_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $query = "UPDATE users SET player_description = :player_description WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':player_description', $player_description);
            $statement->bindValue(':id', $id);
            $statement->execute();
            
            $successMessage .= " Comment added for logged in user!";
        }
    } else {
        echo "Failed to create player.";
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="main.css">
    <script src="https://cdn.tiny.cloud/1/91x1238q9wh2ldf8mgvhy4pnp6xabrihkrrjse0ij3k3whxu/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#player_description',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        });
    </script>
    <style>
        .form-control {
            width: 70%;
        }
    </style>
</head>
<body>
    <?php include('nav_admin.php'); ?>
    <div class="container mt-5">
        <div id="main-content">
            <div class="post">
                <h1 class="text-center">Create New Player</h1>
                <form action="players.php" method="post" enctype="multipart/form-data">
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
                        <select class="form-control" id="position" name="position" required>
                            <option value="">Select Position</option>
                            <option value="Point Guard">Point Guard</option>
                            <option value="Shooting Guard">Shooting Guard</option>
                            <option value="Small Forward">Small Forward</option>
                            <option value="Power Forward">Power Forward</option>
                            <option value="Center">Center</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="skill_rating">Skill Rating:</label>
                        <input type="text" class="form-control" id="skill_rating" name="skill_rating" required>
                    </div> 
                    <div class="form-group">
                        <label for="player_image">Player Image (optional):</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="player_image" name="player_image" onchange="updateFilename(this)">
                            <label class="custom-file-label" for="player_image" id="player_image_label">Choose file</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="player_description"><h4>Interesting Facts</h4></label>
                        <textarea class="form-control" id="player_description" name="player_description" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function updateFilename(input) {
            var filename = input.files[0].name;
            document.getElementById('player_image_label').innerHTML = filename;
        }
    </script>
</body>
</html>
