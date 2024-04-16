<?php
session_start(); // Start the session
// Include database connection
require('connect.php');

// Function to check if user is admin (you may need to adjust this based on your authentication mechanism)
function isAdminUser() {
    // Example: Check if the user is logged in and has admin privileges
    return isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin';
}

// Function to hide a comment from public view
function hideComment($db, $comment_id) {
    $query = "UPDATE comments SET moderation_status = 'hidden' WHERE comment_id = :comment_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
    return $statement->execute();
}

// Function to disemvowel a comment
function disemvowelComment($db, $comment_id) {
    // Retrieve comment text from the database
    $query = "SELECT comment FROM comments WHERE comment_id = :comment_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
    $statement->execute();
    $commentText = $statement->fetchColumn();

    // Disemvowel the comment text
    $disemvoweledText = preg_replace('/[aeiou]/i', '', $commentText);

    // Update the comment with disemvoweled text
    $updateQuery = "UPDATE comments SET comment = :comment WHERE comment_id = :comment_id";
    $updateStatement = $db->prepare($updateQuery);
    $updateStatement->bindParam(':comment', $disemvoweledText, PDO::PARAM_STR);
    $updateStatement->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
    return $updateStatement->execute();
}

// Handle comment moderation actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdminUser()) {
    // Check if a moderation action is specified
    if (isset($_POST['action']) && isset($_POST['comment_id'])) {
        $action = $_POST['action'];
        $comment_id = $_POST['comment_id'];

        // Perform the specified moderation action
        switch ($action) {
            case 'hide':
                hideComment($db, $comment_id);
                break;
            case 'disemvowel':
                disemvowelComment($db, $comment_id);
                break;
            default:
                // Invalid action
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment Moderation</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add custom styles here if needed */
    </style>
</head>
<body>
<?php include('nav_admin.php'); ?>
    <div class="container">
        <h1 class="mt-5">Comment Moderation</h1>

        <?php foreach ($comments as $comment): ?>
            <div class="card mt-3">
                <div class="card-body">
                    <p class="card-text"><?= $comment['comment'] ?></p>
                    
                    <?php if (isAdminUser()): ?>
                        <form action="" method="post">
                            <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                            <select class="form-control" name="action">
                                <option value="hide">Hide</option>
                                <option value="disemvowel">Disemvowel</option>
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    <?php endif; ?>
                    
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

