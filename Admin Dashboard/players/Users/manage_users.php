<?php
// Include necessary files and start session
include('connect.php'); // assuming this file contains database connection settings
session_start();

// Function to handle editing a user
function editUser() {
    global $db;
    if(isset($_GET['id'])) {
        $userId = $_GET['id'];

        // Retrieve user data from the database
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(['id' => $userId]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if($user) {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Update user information in the database
                $username = $_POST['username'];
                $email = $_POST['email'];
                $query = "UPDATE users SET username = :username, email = :email WHERE id = :id";
                $statement = $db->prepare($query);
                $statement->execute(['username' => $username, 'email' => $email, 'id' => $userId]);

                echo "User updated successfully.";
            } else {
                // Display the form to edit a user
                ?>
                <form method='post'>
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

// Function to handle deleting a user
function deleteUser() {
    global $db;
    if(isset($_GET['id'])) {
        $userId = $_GET['id'];

        // Delete the user from the database
        $query = "DELETE FROM users WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(['id' => $userId]);

        echo "User deleted successfully.";
    } else {
        echo "User ID not provided.";
    }
}

// Handle actions
if(isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'edit':
            editUser();
            break;
        case 'delete':
            deleteUser();
            break;
        default:
            echo "Invalid action.";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include('nav_admin.php'); ?>
    
    <h3>Users Table</h3>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Function to display all registered users in a table
                function viewAllUsers() {
                    global $db;
                    $query = "SELECT * FROM users";
                    $statement = $db->query($query);
                    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

                    // Display users
                    if($users):
                        foreach($users as $user):
                ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= $user['username'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td>
                                    <a href="edit.php?action=edit&id=<?= $user['id'] ?>" class="btn btn-primary">Edit</a>
                                    <a href="delete.php?action=delete&id=<?= $user['id'] ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                <?php
                        endforeach;
                    else:
                        echo "<tr><td colspan='4'>No users found.</td></tr>";
                    endif;
                }
                viewAllUsers();
                ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="create_user.php" class="btn btn-success">Add User</a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
