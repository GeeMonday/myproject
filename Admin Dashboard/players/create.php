<?php

/*******w******** 
    
    Name:George Monday
    Date:22/03/2024
    Descripion:

****************/
require('connect.php');
require ('authenticate.php');
// Check if a file was uploaded
if ($_FILES && isset($_FILES['player_image'])) {
    // Accept only PNG and JPEG/JPG files
    $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    $fileType = $_FILES['player_image']['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        echo "Error: Only PNG and JPEG/JPG files are allowed.";
        exit;
    }

    $uploadDir = 'uploads/'; // Directory where uploaded images will be stored
    $uploadFile = $uploadDir . basename($_FILES['player_image']['name']);
    
    // Move the uploaded file to the designated directory
    if (move_uploaded_file($_FILES['player_image']['tmp_name'], $uploadFile)) {
        // Automatically resize the image
        list($width, $height) = getimagesize($uploadFile);
        $newWidth = 200; // Set the new width (you can adjust this value as needed)
        $aspectRatio = $width / $height;
        $newHeight = $newWidth / $aspectRatio;
        
        // Create a new image from the uploaded file
        if ($fileType === 'image/png') {
            $image = imagecreatefrompng($uploadFile);
        } else {
            $image = imagecreatefromjpeg($uploadFile);
        }

        // Resize the image
        $resizedImage = imagescale($image, $newWidth, $newHeight);
        
        // Save the resized image
        if ($fileType === 'image/png') {
            imagepng($resizedImage, $uploadFile);
        } else {
            imagejpeg($resizedImage, $uploadFile);
        }

        // Free up memory
        imagedestroy($image);
        imagedestroy($resizedImage);

        // Debug: Check if file was uploaded successfully
    echo "File uploaded to: " . $uploadFile;

        echo "File is valid, and was successfully uploaded and resized.";
    } else {
        echo "Possible file upload attack!";
    }
}

if ($_POST && !empty($_POST['player_name']) && !empty($_POST['team']) && !empty($_POST['position']) && !empty($_POST['skill_rating'])) {
    // Sanitize user input
    $player_name = filter_input(INPUT_POST, 'player_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $team = filter_input(INPUT_POST, 'team', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $skill_rating = filter_input(INPUT_POST, 'skill_rating', FILTER_SANITIZE_NUMBER_INT);
    $player_image = filter_input(INPUT_POST, 'player_image', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

     // Use the path where the image was uploaded
    $player_image = $uploadFile;
// bind paramters
$query = "INSERT INTO nbaeliteroster (player_name, team, position, skill_rating, player_image) VALUES (:player_name, :team, :position, :skill_rating, :player_image)";
$statement = $db->prepare($query);
$statement->bindValue(':player_name', $player_name);
$statement->bindValue(':team', $team);
$statement->bindValue(':position', $position);
$statement->bindValue(':skill_rating', $skill_rating);
$statement->bindValue(':player_image', $player_image);

    
    // Execute the INSERT query
    if($statement->execute()){
        echo "Player created successfully!";
        
        // Get the ID of the newly inserted player
        $name = $db->lastInsertId();
        
        // Save the comment in the comments table
        $query = "INSERT INTO comments (name, comment) VALUES (:name, :comment)";
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':comment', $comments);
        $statement->execute();
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
    <?php include('nav_admin.php'); ?>
    <div class="container mt-5">
        <div id="main-content">
            <div class="post">
            <h1 class="text-center">Create New Player</h1>

                <!-- HTML form for creating a new table to the Elite Rosters database -->
                <form action="players.php" method="post">
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
    <label for="player_image">Player Image(optional):</label>
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="player_image" name="player_image" onchange="updateFilename(this)">
        <label class="custom-file-label" for="player_image" id="player_image_label">Choose file</label>
    </div>
</div>

    </div>
                    <div class="form-group">
                 <label for="player_description"><h4>Interesting Facts</h4></label>
                <!-- Use a textarea for interesting facts -->
                <textarea class="form-control" id="player_description" name="player_description" rows="4"></textarea>
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
    <script>
    function updateFilename(input) {
        // Get the file name
        var filename = input.files[0].name;
        // Update the label text
        document.getElementById('player_image_label').innerHTML = filename;
    }
</script>

</body>
</html>
