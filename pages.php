<?php
require('connect.php');
// Check if category ID is provided
if (isset($_GET['category_id'])) {
    // Sanitize category ID
    $category_id = filter_input(INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT);

    // Query the database to retrieve pages associated with the selected category
    $query = "SELECT * FROM positions WHERE category_id = :category_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':category_id', $category_id);
    $statement->execute();
    $pages = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Display pages associated with the selected category
    if ($pages) {
        echo "<h2>Pages Associated with Category</h2>";
        echo "<ul>";
        foreach ($pages as $page) {
            echo "<li>{$page['positions_name']}</li>";
        }
        echo "</ul>";
    } else {
        echo "No pages found for this category.";
    }
} else {
    echo "Category ID is not provided.";
}
?>

