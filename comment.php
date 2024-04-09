<?php
session_start(); // Start session for CAPTCHA verification

// Database connection
require('connect.php');

// Function to submit a comment
function submitComment($db, $page_id, $name, $comment) {
    $query = "INSERT INTO comments (page_id, name, comment) VALUES (:page_id, :name, :comment)";
    $statement = $db->prepare($query);
    $statement->bindParam(':page_id', $page_id, PDO::PARAM_INT);
    $statement->bindParam(':page_name', $name, PDO::PARAM_STR);
    $statement->bindParam(':comment', $comment, PDO::PARAM_STR);
    return $statement->execute();
}

// Handle form submission for comments
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    // Validate and sanitize form data
    $page_id = filter_input(INPUT_POST, 'page_id', FILTER_VALIDATE_INT);
    $name = filter_input(INPUT_POST, 'page_name', FILTER_SANITIZE_STRING);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $captcha_input = $_POST['captcha'];

    // Validate CAPTCHA
    if (!isset($_SESSION['captcha']) || $_SESSION['captcha'] !== $captcha_input) {
        // Invalid CAPTCHA
        echo "Error: Invalid CAPTCHA. Please try again.";
        exit;
    }

    // Check if form inputs are not empty
    if ($page_id && $name && $comment) {
        // Submit comment to the database
        if (submitComment($db, $page_id, $page_name, $comment)) {
            // Redirect back to the page after successful submission
            header('Location: pages.php?id=' . $page_id);
            exit;
        } else {
            // Handle database error
            echo "Error: Unable to submit comment.";
        }
    } else {
        // Handle invalid form data
        echo "Error: Please fill in all required fields.";
    }
} else {
    // Redirect if form is not submitted
    header('Location: pages.php');
    exit;
}
?>
