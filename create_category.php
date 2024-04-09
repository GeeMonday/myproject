<?php
require('connect.php'); 
if ($_POST && !empty($_POST['category_name'])) {
// Sanitize user input
$category_name = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_STRING);

// Build the parameterized SQL query and bind to the above sanitized values
$query = "INSERT INTO categories (category_name) VALUES (:category_name)";
$statement = $db->prepare($query);

// Bind values to the parameters
$statement->bindParam(':category_name', $category_name, PDO::PARAM_STR);

// Execute the INSERT.
// execute() will check for possible SQL injection and remove if necessary
if($statement->execute()){
    echo "category created successfully!";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Create Category</h2>
        <form action="category.php" method="post">
            <div class="form-group">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Category</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
