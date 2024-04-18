<div class="form-group">
    <label for="category">Category:</label>
    <select class="form-control" id="category" name="category">
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
