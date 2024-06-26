<?php
session_start(); // Start the session

// Check if user is logged in and the message hasn't been displayed yet
if(isset($_SESSION['id']) && !isset($_SESSION['login_message_displayed'])) {
    echo "Login successful!";
    $_SESSION['login_message_displayed'] = true; // Set flag to indicate the message has been displayed
} 


// Check if user is created successfully
if(isset($_SESSION['user_created'])) {
    echo "User created successfully!";
    // Unset the session variable to avoid showing this message again on page refresh
    unset($_SESSION['user_created']);
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA Elite Roster</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
<?php include('nav.php'); ?>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <div class="container">
        <div id='header' class="jumbotron">
            <h2><a href='index.php'>NBA Elite Roster</a></h2>
            <p>
                The NBA Elite Roster showcases some of the most talented and impactful players from the 90's era in the league. 
                From iconic legends like Michael Jordan and Magic Johnson to dominant big men such as Hakeem Olajuwon and Karl Malone, 
                each player left a lasting legacy on the game of basketball. Whether it's scoring at will like Jordan and Johnson or dominating the paint like Olajuwon and Shaquille O'Neal, 
                these elite athletes defined an entire era of basketball with their unparalleled skills and competitiveness.
                With a diverse range of positions and skill ratings, the NBA Elite Roster pays tribute to the greatness of the 90's NBA era.
            </p>
        </div>
</div>

        <footer id='footer' class="text-center">
            <h3>CopyRight 2024 - All Right Reserved. </h3>
        </footer>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
