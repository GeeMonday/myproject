<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Selection</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php
// Include necessary files and start session
include('connect.php'); // assuming this file contains database connection settings
session_start();

// Fetch categories from the database
$query = "SELECT * FROM categories";
$statement = $db->query($query);
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="form-group">
        <label for="category">Category:</label>
        <select class="form-control" id="category" name="category">
            <!-- Populate dropdown options dynamically with categories from database -->
            <?php
            // Iterate through the categories and populate dropdown options
            foreach($categories as $category) {
                echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
            }
            ?>
        </select>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
