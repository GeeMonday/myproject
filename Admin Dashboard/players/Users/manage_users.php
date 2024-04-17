<?php
// Include necessary files and start session
include('connect.php'); // assuming this file contains database connection settings
session_start();
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
<div class="container mt-5">
    <h2 class="mb-4">Page Administration</h2>
    
    <h3>All Registered Users:</h3>
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
                                    <a href="admin.php?action=edit&id=<?= $user['id'] ?>" class="btn btn-primary">Edit</a>
                                    <a href="admin.php?action=delete&id=<?= $user['id'] ?>" class="btn btn-danger">Delete</a>
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
