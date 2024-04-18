<?php
// Include necessary files and start session
include('connect.php'); // assuming this file contains database connection settings
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $category_id = $_POST['category_id'];
    $new_category_name = $_POST['new_category_name'];

    // Update category name in the database
    $query = "UPDATE categories SET category_name = :new_category_name WHERE category_id = :category_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':new_category_name', $new_category_name, PDO::PARAM_STR);
    $statement->bindParam(':category_id', $category_id, PDO::PARAM_INT);

    try {
        $statement->execute();
        echo "Category updated successfully.";
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error updating category: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Category</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<form action="update_category.php" method="post">
    <div class="form-group">
        <label for="category_id">Select Category:</label>
        <select class="form-control" id="category_id" name="category_id">
            <!-- Populate dropdown options dynamically with categories from database -->
            <?php            
           // Fetch categories from the database
            $query = "SELECT * FROM categories";
            $statement = $db->query($query);
            $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Iterate through the categories and populate dropdown options
            foreach($categories as $category) {
                echo "<option value='" . $category['category_id'] . "'>" . $category['category_name'] . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="new_category_name">New Category Name:</label>
        <input type="text" class="form-control" id="new_category_name" name="new_category_name">
    </div>
    <button type="submit" class="btn btn-primary">Update Category</button>
</form>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
