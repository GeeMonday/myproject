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
