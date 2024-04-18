<?php
require('connect.php');

// Function to resize image to 300x300 pixels
function resizeImage($sourceFile, $targetFile, $maxWidth = 300, $maxHeight = 300, $quality = 90) {
    list($origWidth, $origHeight, $type) = getimagesize($sourceFile);

    $width = $origWidth;
    $height = $origHeight;

    // Calculate aspect ratio
    $aspectRatio = $width / $height;

    // Calculate new dimensions while preserving aspect ratio
    if ($width > $height && $width > $maxWidth) {
        $width = $maxWidth;
        $height = $width / $aspectRatio;
    } elseif ($height > $maxHeight) {
        $height = $maxHeight;
        $width = $height * $aspectRatio;
    }

    // Create a new image resource
    $newImage = imagecreatetruecolor($width, $height);

    // Load the original image
    $source = imagecreatefromjpeg($sourceFile);

    // Resize the image
    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

    // Save the resized image
    imagejpeg($newImage, $targetFile, $quality);

    // Free up memory
    imagedestroy($source);
    imagedestroy($newImage);
}

// Check if the form is submitted and category_name is not empty
if ($_POST && !empty($_POST['category_name'])) {
    // Sanitize user input
    $category_name = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_STRING);

    // Check if an image is uploaded
    if ($_FILES['category_image']['error'] == UPLOAD_ERR_OK) {
        // Get file information
        $fileName = $_FILES['category_image']['name'];
        $tmpName = $_FILES['category_image']['tmp_name'];
        $fileSize = $_FILES['category_image']['size'];
        $fileType = $_FILES['category_image']['type'];

        // Check if the uploaded file is an image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes)) {
            // Set upload directory
            $uploadDir = 'uploads/';

            // Generate a unique filename
            $filePath = $uploadDir . uniqid() . '_' . $fileName;

            // Resize and save the image
            resizeImage($tmpName, $filePath);

            // Build the parameterized SQL query and bind to the above sanitized values
            $query = "INSERT INTO positions (positions_name, image_url) VALUES (:category_name, :category_image)";
            $statement = $db->prepare($query);

            // Bind values to the parameters
            $statement->bindParam(':category_name', $category_name, PDO::PARAM_STR);
            $statement->bindParam(':category_image', $filePath, PDO::PARAM_STR);

            // Execute the INSERT.
            // execute() will check for possible SQL injection and remove if necessary
            if ($statement->execute()) {
                // Redirect to category.php after successful insertion
                header("Location: categoryy.php");
                exit();
            } else {
                // Handle the case where the insertion fails
                $message = "Error creating category.";
            }
        } else {
            // Handle invalid file type
            $message = "Invalid file type. Only JPG, PNG, and GIF files are allowed.";
        }
    } else {
        // Handle no file uploaded
        $message = "No file uploaded.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Create Category</h1>

    <!-- Display success message if set -->
    <?php if (!empty($message)) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Category creation form -->
    <form action="create_category.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="category_name">Category Name:</label>
            <input type="text" class="form-control" id="category_name" name="category_name">
        </div>
        <div class="form-group">
            <label for="category_image">Category Image:</label>
            <input type="file" class="form-control-file" id="category_image" name="category_image">
        </div>
        <button type="submit" class="btn btn-primary">Create Category</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
