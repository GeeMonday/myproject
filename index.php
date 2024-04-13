<?php
session_start(); // Start the session

// Check if user is logged in
if(isset($_SESSION['id'])) {
    echo "Login successful!";
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
        <div class="jumbotron">
            <h1 class="display-3">About Us</h1>
        <h2 class="display-4">Welcome to NBA Elite Rooster!</h2>
        <p class="lead">At NBA Elite Rooster, we're passionate about basketball and dedicated to providing the ultimate experience for fans and enthusiasts alike.</p>
        <hr class="my-4">
        <p>We are your go-to destination for comprehensive coverage of the NBA, from player stats and game analysis to in-depth articles and commentary.</p>
        <p>Our mission is to empower basketball enthusiasts with the latest news, insights, and updates, making NBA Elite Rooster the premier content management site for fans around the world.</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="index.php" role="button">Explore Now</a>
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
