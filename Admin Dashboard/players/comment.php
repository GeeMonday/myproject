<?php

// Database connection
require('connect.php');

// Function to submit a comment
function submitComment($db, $name, $comment) {
    $query = "INSERT INTO comments (name, comment) VALUES (:name, :comment)";
    $statement = $db->prepare($query);
    $statement->bindParam(':name', $name, PDO::PARAM_STR);
    $statement->bindParam(':comment', $comment, PDO::PARAM_STR);
    return $statement->execute();
}

// Handle form submission for comments
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    // Validate and sanitize form data
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $captcha_input = $_POST['captchaInput']; // Change to captchaInput

    
    // Check if form inputs are not empty
    if ($name && $comment) {
        // Submit comment to the database
        if (submitComment($db, $name, $comment)) {
            // Show success message
            echo "Comment submitted successfully!";
        } else {
            // Handle database error
            echo "Error: Unable to submit comment.";
        }
    } else {
        // Handle invalid form data
        echo "Error: Please fill in all required fields.";
    }
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Comment</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS to reduce container size */
        .custom-container {
            max-width: 400px;
            margin-top: 100px;
            border-radius: 10px;
            background-color: #ffffff;
            padding: 40px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1)
        }
    </style>
</head>
<body>
<?php include('nav_admin.php'); ?>
<div class="container custom-container">
    <?php 
    // Check if the ID parameter is set in the URL
    if (isset($_GET['player_id'])) {
        // Get the player ID from the URL parameter
        $player_id = $_GET['player_id'];

        // Retrieve the player by ID
        $selectedPlayer = getPlayerById($db, $player_id);

        // If the player is found, display the comment form
        if ($selectedPlayer) {
            echo "<h3>Interesting facts for {$selectedPlayer['player_name']}</h3>";
        }
    }
    ?>
    <!-- HTML Form with CAPTCHA -->
<form id="commentForm" action="comment.php" method="post">
    <div class="form-group">
        <label for="name">Your Name:</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Name" maxlength="30" required>
    </div>
    <div class="form-group">
        <label for="comment">Your Comment:</label>
        <textarea class="form-control" id="comment" name="comment" placeholder="Enter your comment" maxlength="200" required></textarea>
    </div>
    <!-- Display CAPTCHA image -->
    <canvas id="captchaCanvas" width="150" height="50"></canvas>
    <input type="hidden" id="captcha" name="captcha">
    <button type="button" id="refreshCaptcha" class="btn btn-secondary">Refresh CAPTCHA</button>
    <div class="form-group">
        <label for="captchaInput">Enter CAPTCHA:</label>
        <input type="text" class="form-control" id="captchaInput" name="captchaInput" placeholder="Enter CAPTCHA" maxlength="10" required>
    </div>
    <div class="form-group">
        <label for="reenter_captcha">Re-enter CAPTCHA:</label>
        <input type="text" class="form-control" id="reenter_captcha" name="reenter_captcha" placeholder="Re-enter CAPTCHA" maxlength="10" required>
    </div>
    <button type="submit" class="btn btn-primary" name="submit_comment">Submit Comment</button>
</form>
</div>
<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- JavaScript to Generate CAPTCHA Image and Refresh -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    refreshCaptcha();

    // Add event listener for refreshing CAPTCHA
    document.getElementById('refreshCaptcha').addEventListener('click', function() {
        refreshCaptcha();
    });

    // Function to generate and display CAPTCHA
    function refreshCaptcha() {
        var captchaCanvas = document.getElementById('captchaCanvas');
        var ctx = captchaCanvas.getContext('2d');
        var captcha = generateCaptcha();
        document.getElementById('captcha').value = captcha; // Set CAPTCHA value in hidden input

        // Clear canvas and draw new CAPTCHA text
        ctx.clearRect(0, 0, captchaCanvas.width, captchaCanvas.height);
        ctx.font = '30px Arial';
        ctx.fillText(captcha, 10, 35);
    }

    // Function to generate random CAPTCHA value
    function generateCaptcha() {
        return Math.random().toString(36).substr(2, 6).toUpperCase(); // Example: "ABCDEF"
    }
});
</script>
</body>
</html>
