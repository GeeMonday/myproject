<form action="update_category.php" method="post">
    <div class="form-group">
        <label for="category_id">Select Category:</label>
        <select class="form-control" id="category_id" name="category_id">
            <!-- Populate dropdown options dynamically with categories from database -->
            <?php
            // Include necessary files and start session
            include('connect.php'); // assuming this file contains database connection settings
            session_start();

            // Fetch categories from the database
            $query = "SELECT * FROM categories";
            $statement = $db->query($query);
            $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Iterate through the categories and populate dropdown options
            foreach($categories as $category) {
                echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
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
