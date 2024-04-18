<?php
require('connect.php'); 
// Start the session
session_start();

// Check if user is logged in
$userLoggedIn = isset($_SESSION['id']);

// Retrieve newly created categories from the database within the last minute
$newCategoriesQuery = $db->query("SELECT * FROM positions WHERE created_at >= NOW() - INTERVAL 3 MINUTE");
$newCategories = $newCategoriesQuery->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .category-box {
            width: 300px;
            margin: 1rem;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .category-image {
            max-width: 100%;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            width: 400px;
            height: 300px;
            object-fit: cover;
        }

        .button-container {
            position: absolute;
            top: 70px;
            right: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Categories</h2>
    <div class="row">
        <?php foreach ($newCategories as $category): ?>
            <div class="col-md-4">
                <div class="category-box">
                    <img src="<?= $category['image_url'] ?>" class="category-image">
                    <p><a href='pages.php?category_id=<?= $category['category_id'] ?>'><?= $category['positions_name'] ?></a></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
