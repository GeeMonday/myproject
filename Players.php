<?php
require('connect.php');
if ($_POST && !empty($_POST['player_id']) && !empty($_POST['player_name']) && !empty($_POST['team']) && !empty($_POST['position']) && !empty($_POST['skill_rating'])) {
    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $player_id = filter_input(INPUT_POST, 'player_id', FILTER_SANITIZE_NUMBER_INT);
    $player_name = filter_input(INPUT_POST, 'player_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $team = filter_input(INPUT_POST, 'team', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $skill_rating = filter_input(INPUT_POST, 'skill_rating', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO nbaeliteroster (player_id, player_name, team, position, skill_rating) VALUES (:player_id, :player_name, :team, :position, :skill_rating)";
    $statement = $db->prepare($query);
    
    // Bind values to the parameters
    $statement->bindValue(':player_id', $player_id);
    $statement->bindValue(':player_name', $player_name);
    $statement->bindValue(':team', $team);
    $statement->bindValue(':position', $position);
    $statement->bindValue(':skill_rating', $skill_rating);
    
    // Execute the INSERT.
    // execute() will check for possible SQL injection and remove if necessary
    if($statement->execute()){
        echo "success!";
    }
}
?>
  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>NBA Elite Roster</title>
</head>
<body>
<?php include('nav.php'); ?>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <div id='main-content'>
        <div id='header'>
            <h2><a href='index.php'>Players</a></h2>
<?php
        // Retrieve all data from the database
        $query = "SELECT * FROM nbaeliteroster";
        $statement = $db->query($query);
        $players = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are any players
        if ($players) :
        ?>
            <!-- Display the data in a table -->
            <table class="nba-roster">
                <thead>
                    <tr>
                        <th>Player ID</th>
                        <th>Player Name</th>
                        <th>Team</th>
                        <th>Position</th>
                        <th>Skill Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($players as $player) : ?>
                        <tr>
                            <!-- Check if 'player_id' key exists -->
                            <td><?= isset($player['player_id']) ? $player['player_id'] : 'N/A' ?></td>
                            <!-- Check if 'player_name' key exists  and display player name with  link to post page -->
                            <td>
                          <?php if(isset($player['player_id'])): ?>
                        <h2><a href="post.php?player_id=<?= $player['player_id'] ?>"><?php echo $player['player_name']; ?></a></h2>
                        <?php endif; ?>
                            </td>
                            <!-- Check if 'team' key exists -->
                            <td><?= isset($player['team']) ? $player['team'] : 'N/A' ?></td>
                            <!-- Check if 'position' key exists -->
                            <td><?= isset($player['position']) ? $player['position'] : 'N/A' ?></td>
                            <!-- Check if 'skill_rating' key exists -->
                            <td><?= isset($player['skill_rating']) ? $player['skill_rating'] : 'N/A' ?></td>
                            <!-- Add edit and delete buttons -->
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <!-- Display a message if there are no players -->
            <p>No players found.</p>
        <?php endif; ?> 

        <footer id='footer'>
            <h3>Copyright 2024 - All Right Reserved. </h3>
        </footer>
    </div>
</body>
</html>