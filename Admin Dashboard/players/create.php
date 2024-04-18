<?php
require('connect.php');
require('authenticate.php');

// Function to check if uploaded file is an image
function isImage($file) {
    $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    $fileType = mime_content_type($file);
    return in_array($fileType, $allowedTypes);
}

// Function to resize uploaded image
function resizeImage($sourceFile, $targetFile, $maxWidth = 300, $maxHeight = 300, $quality = 90) {
    list($origWidth, $origHeight, $type) = getimagesize($sourceFile);

    // Calculate new dimensions while preserving aspect ratio
    $aspectRatio = $origWidth / $origHeight;
    if ($origWidth > $origHeight) {
        $width = $maxWidth;
        $height = $maxWidth / $aspectRatio;
    } else {
        $height = $maxHeight;
        $width = $maxHeight * $aspectRatio;
    }

    // Create a new image resource
    $newImage = imagecreatetruecolor($width, $height);

    // Load the original image
    if ($type === IMAGETYPE_JPEG) {
        $source = imagecreatefromjpeg($sourceFile);
    } elseif ($type === IMAGETYPE_PNG) {
        $source = imagecreatefrompng($sourceFile);
    } else {
        return false; // Unsupported image type
    }

    // Resize the image
    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

    // Save the resized image
    imagejpeg($newImage, $targetFile, $quality);

    // Free up memory
    imagedestroy($source);
    imagedestroy($newImage);

    return true;
}

// Function to handle image upload
function uploadImage($file) {
    $uploadDir = 'Admin Dashboard/players/player_image/';
    $filename = uniqid() . '_' . basename($file['name']);
    $uploadFile = $uploadDir . $filename;

    if (isImage($file['tmp_name']) && move_uploaded_file($file['tmp_name'], $uploadFile)) {
        // Resize uploaded image
        $resizedFile = 'resized_' . $filename;
        $resizedFilePath = $uploadDir . $resizedFile;
        resizeImage($uploadFile, $resizedFilePath);
        
        // Return the relative URL of the uploaded image
        return $resizedFilePath;
    } else {
        return false;
    }
}

// Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['player_image'])) {
    $imageUrl = uploadImage($_FILES['player_image']);
    if ($imageUrl) {
        // Image upload successful, save the URL to database
        // Perform database insertion here
        // Example: INSERT INTO nbaeliteroster (player_image) VALUES (:imageUrl)
        // Replace this with your actual database insertion code
    } else {
        // Image upload failed
        echo "Error uploading image.";
    }
}

// Check if form is submitted and perform database insertion
if ($_POST && !empty($_POST['player_name']) && !empty($_POST['team']) && !empty($_POST['position']) && !empty($_POST['skill_rating'])) {
    // Get form data
    $player_name = filter_input(INPUT_POST, 'player_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $team = filter_input(INPUT_POST, 'team', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $skill_rating = filter_input(INPUT_POST, 'skill_rating', FILTER_SANITIZE_NUMBER_INT);
    
    // Upload player image and get its URL
    $player_image = uploadPlayerImage();

    // Prepare and execute SQL query
    $query = "INSERT INTO nbaeliteroster (player_name, team, position, skill_rating, player_image) VALUES (:player_name, :team, :position, :skill_rating, :player_image)";
    $statement = $db->prepare($query);
    $statement->bindParam(':player_name', $player_name, PDO::PARAM_STR);
    $statement->bindParam(':team', $team, PDO::PARAM_STR);
    $statement->bindParam(':position', $position, PDO::PARAM_STR);
    $statement->bindParam(':skill_rating', $skill_rating, PDO::PARAM_INT);
    $statement->bindParam(':player_image', $player_image, PDO::PARAM_STR);
   

    if ($statement->execute()) {
        $successMessage = "Player created successfully!";
    }
        // Check if the user is logged in
        if ($userLoggedIn) {
            // Get the user's ID from the session
            $id = $_SESSION['id'];

            // Update the player_description in the users table
            $query = "UPDATE users SET player_description = :player_description WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':player_description', $player_description, PDO::PARAM_STR);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            
            if ($statement->execute()) {
                // Success message or further action
            } else {
                echo "Failed to update player description for user.";
            }
        }
    } else {
        echo "Failed to create player.";
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
