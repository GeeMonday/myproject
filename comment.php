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
    $captcha_input = $_POST['captcha'];

    // Validate CAPTCHA
    if (!isset($_SESSION['captcha']) || $_SESSION['captcha'] !== $captcha_input) {
        // Invalid CAPTCHA
        echo "Error: Invalid CAPTCHA. Please try again.";
        exit;
    }

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Comment</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('nav_guest.php'); ?>
    <div class="container">
        <h2>Submit Comment</h2>
        <form action="comment.php" method="post">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name">
            </div>
            <div class="form-group">
                <label for="comment">Your Comment:</label>
                <textarea class="form-control" id="comment" name="comment" placeholder="Enter your comment" required></textarea>
            </div>
            <!-- Display CAPTCHA image -->
            <img src="captcha.php" class="img-fluid" alt="CAPTCHA Image"><br>
            <div class="form-group">
                <label for="captcha">Enter CAPTCHA:</label>
                <input type="text" class="form-control" id="captcha" name="captcha" placeholder="Enter CAPTCHA" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_comment">Submit Comment</button>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
