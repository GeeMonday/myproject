<?php
// Include necessary files and start session
include('connect.php'); // assuming this file contains database connection settings
session_start();

// Function to handle deleting a user
function deleteUser() {
    global $db;
    if(isset($_GET['id'])) {
        $userId = $_GET['id'];

        // Delete the user from the database
        $query = "DELETE FROM users WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(['id' => $userId]);

        echo "User deleted successfully.";
    } else {
        echo "User ID not provided.";
    }
}

// Handle deleting action
if(isset($_GET['action']) && $_GET['action'] === 'delete') {
    deleteUser();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<!-- Display message after user deletion -->
<?php if(isset($_GET['action']) && $_GET['action'] === 'delete'): ?>
    <div class="alert alert-success mt-3" role="alert">
        <?php echo "User deleted successfully."; ?>
    </div>
<?php endif; ?>

</body>
</html>

