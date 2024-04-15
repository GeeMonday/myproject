<?php
require('connect.php');
session_start(); // Start the session


// Check if user is logged in
$userLoggedIn = isset($_SESSION['id']);

// Check if category_id is provided
if (isset($_GET['category_id'])):
    // Sanitize category_id
    $category_id = filter_input(INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT);

    // Query the database to retrieve the position name associated with the selected category
    $query = "SELECT positions_name FROM positions WHERE category_id = :category_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':category_id', $category_id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result):
        $positions_name = $result['positions_name'];

        // Query the database to retrieve players associated with the selected position
        $query = "SELECT player_id, player_name FROM nbaeliteroster WHERE position = :position";
        $statement = $db->prepare($query);
        $statement->bindParam(':position', $positions_name);
        $statement->execute();
        $players = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Display players associated with the selected position
        if ($players):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>NBA Elite Roster</title>
</head>
<body>
<?php include('nav.php'); ?>
    <div class="container">
        <h2 class="mt-4">Players Associated with Position: <?php echo $positions_name; ?></h2>
        <ul class="list-group">
<?php foreach ($players as $player): ?>
            <li class="list-group-item"><a href="post.php?player_id=<?php echo $player['player_id']; ?>" style="font-size: 20px;"><?php echo $player['player_name']; ?></a></li>
<?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
<?php
        else:
            echo "<div class='container'><p class='mt-4'>No players found for this position.</p></div>";
        endif;
    else:
        echo "<div class='container'><p class='mt-4'>No position found for this category.</p></div>";
    endif;
else:
    echo "<div class='container'><p class='mt-4'>Category ID is not provided.</p></div>";
endif;
?>

