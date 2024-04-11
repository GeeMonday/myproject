<?php
// Check if the user is logged in and set $userLoggedIn accordingly
$userLoggedIn = isset($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA Elite Rooster</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">NBA Elite Rooster</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="category.php">Category</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="players.php">Players</a>
        </li>
        <?php
        // Check if the user is logged in
        if ($userLoggedIn) {
            // If logged in, show username or "Log in" if username is not available
            echo '<li class="nav-item dropdown">';
            echo '<a class="nav-link dropdown-toggle" href="login.php" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            echo ($usernameFromDatabase ? $usernameFromDatabase : 'Log in');
            echo '</a>';
            echo '<div class="dropdown-menu" aria-labelledby="userDropdown">';
            if ($usernameFromDatabase) {
                echo '<a class="dropdown-item" href="create_user.php">Profile</a>';
                echo '<div class="dropdown-divider"></div>';
                echo '<a class="dropdown-item" href="logout.php">Log out</a>';
            } else {
                echo '<a class="dropdown-item" href="login.php">Log in</a>';
            }
            echo '</div>';
            echo '</li>';
        } else {
            // If not logged in, show "Log in"
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="login.php">Log in</a>';
            echo '</li>';
        }
        ?>
        <li class="nav-item">
            <a class="nav-link" href="about_us.php">About us</a>
        </li>
        <?php
        // Show "Admins" link only if user is logged in
        if ($userLoggedIn) {
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="admin.php">Admins</a>';
            echo '</li>';
        }
        ?>
    </ul>
</div>

</nav>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
        </div>
        <div class="col-md-9">
            <!-- Main Content -->
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>