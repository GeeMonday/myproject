<?php
// Include necessary files and start session
include('connect.php'); // assuming this file contains database connection settings
session_start();

// Function to handle editing a user
function editUser() {
    global $db;
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        // Retrieve user data from the database
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(['id' => $id]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if($user) {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Update user information in the database
                $username = $_POST['username'];
                $email = $_POST['email'];
                $query = "UPDATE users SET username = :username, email = :email WHERE id = :id";
                $statement = $db->prepare($query);
                $statement->execute(['username' => $username, 'email' => $email, 'id' => $id]);
            } else {
                // Display the form to edit a user
                ?>
                <form action='edit_user.php?action=edit&id=<?= $id ?>' method='post'>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
                <?php
            }
        } else {
            echo "User not found.";
        }
    } else {
        echo "User ID not provided.";
    }
}

// Handle editing action
if(isset($_GET['action']) && $_GET['action'] === 'edit') {
    editUser();
}
?>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Display message after form submission -->
<?php if(isset($_POST['username']) && isset($_POST['email'])): ?>
    <div class="alert alert-success mt-3" role="alert">
        User updated successfully.
    </div>
<?php endif; ?>

