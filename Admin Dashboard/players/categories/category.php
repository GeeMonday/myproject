
<?php
// Include necessary files and start session
include('connect.php'); // assuming this file contains database connection settings
session_start();

// Check if user is logged in as admin, if not redirect to login page
// Implement your admin authentication logic here

// Function to fetch all categories from the database
function getAllCategories() {
    global $db;
    $query = "SELECT * FROM categories";
    $statement = $db->query($query);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Display the page content
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Categories</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Categories</h1>
        <div class="list-group mt-3">
            <!-- Link to create a new category -->
            <a href="create_category.php" class="list-group-item list-group-item-action">Create New Category</a>
            <!-- Link to update a category -->
            <a href="update_category.php" class="list-group-item list-group-item-action">Update Category</a>
            <!-- Link to assign a category to a page -->
            <a href="assign_category.php" class="list-group-item list-group-item-action">Assign Category to Page</a>
        </div>
    </div>
</body>
</html>
