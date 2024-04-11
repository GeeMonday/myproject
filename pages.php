<?php
require('connect.php');

// Check if category_id is provided
if (isset($_GET['category_id'])) {
    // Sanitize category_id
    $category_id = filter_input(INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT);

    // Query the database to retrieve the position name associated with the selected category
    $query = "SELECT positions_name FROM positions WHERE category_id = :category_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':category_id', $category_id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $positions_name = $result['positions_name'];

        // Query the database to retrieve players associated with the selected position
        $query = "SELECT player_id, player_name FROM nbaeliteroster WHERE position = :position";
        $statement = $db->prepare($query);
        $statement->bindParam(':position', $positions_name);
        $statement->execute();
        $players = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Display players associated with the selected position
        if ($players) {
            ?>
            <div class="container">
                <h2 class="mt-4">Players Associated with Position: <?php echo $positions_name; ?></h2>
                <ul class="list-group">
                    <?php foreach ($players as $player) { ?>
                        <li class="list-group-item"><a href="post.php?player_id=<?php echo $player['player_id']; ?>" style="font-size: 20px;"><?php echo $player['player_name']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <?php
        } else {
            echo "<div class='container'><p class='mt-4'>No players found for this position.</p></div>";
        }
    } else {
        echo "<div class='container'><p class='mt-4'>No position found for this category.</p></div>";
    }
} else {
    echo "<div class='container'><p class='mt-4'>Category ID is not provided.</p></div>";
}
?>
