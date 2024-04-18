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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['category'])) {
        $categoryId = $_POST['category'];
        // Here you would typically insert a record into your pages table to associate the selected category with a page
        // Example: INSERT INTO pages (category_id) VALUES (:categoryId)
        // You'll need to adjust this query based on your database schema
        // Execute the query using PDO prepared statements
        // Redirect to a success page or display a success message
    } else {
        // Handle the case where no category is selected
    }
}
?>

<div class="container mt-5">
    <h1>Assign Category to Page</h1>
    <form method="post">
        <div class="form-group">
            <label for="category">Category:</label>
            <select class="form-control" id="category" name="category">
                <!-- Populate dropdown options dynamically with categories from database -->
                <?php
                // Iterate through the categories and populate dropdown options
                foreach($categories as $category) {
                    echo "<option value='" . $category['category_id'] . "'>" . $category['category_name'] . "</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assign Category</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
