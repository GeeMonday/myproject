<?php
// Include necessary files and start session
include('config.php'); // assuming this file contains database connection settings
session_start();

// Function to display all registered users
function viewAllUsers() {
    global $db;
    $query = "SELECT * FROM users";
    $statement = $db->query($query);
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Display users
    if($users):
        foreach($users as $user):
?>
            User ID: <?= $user['id'] ?> | Username: <?= $user['username'] ?> | Email: <?= $user['email'] ?>
            <a href="admin.php?action=edit&id=<?= $user['id'] ?>">Edit</a>
            <a href="admin.php?action=delete&id=<?= $user['id'] ?>">Delete</a><br>
<?php
        endforeach;
    else:
        echo "No users found.";
    endif;
}

// Function to add a user
function addUser() {
    global $db;
    if($_SERVER['REQUEST_METHOD'] === 'POST'):
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // Insert new user into the database
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $statement = $db->prepare($query);
        $statement->execute(['username' => $username, 'email' => $email, 'password' => $password]);

        echo "User added successfully.";
    else:
        // Display the form to add a new user
?>
        <form method='post'>
            Username: <input type='text' name='username'><br>
            Email: <input type='email' name='email'><br>
            Password: <input type='password' name='password'><br>
            <input type='submit' value='Add User'>
        </form>
<?php
    endif;
}

// Function to update a user
function editUser() {
    global $db;
    if($_SERVER['REQUEST_METHOD'] === 'POST'):
        $userId = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];

        // Update user information in the database
        $query = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(['username' => $username, 'email' => $email, 'id' => $userId]);

        echo "User updated successfully.";
    else:
        // Display the form to update a user
        $userId = $_GET['id']; // Assuming you have a URL parameter 'id' for the user to update
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(['id' => $userId]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if($user):
?>
            <form method='post'>
                Username: <input type='text' name='username' value='<?= $user['username'] ?>'><br>
                Email: <input type='email' name='email' value='<?= $user['email'] ?>'><br>
                <input type='hidden' name='user_id' value='<?= $user['id'] ?>'>
                <input type='submit' value='Update User'>
            </form>
<?php
        else:
            echo "User not found.";
        endif;
    endif;
}

// Function to delete a user
function deleteUser() {
    global $db;
    if(isset($_GET['id'])):
        $userId = $_GET['id'];

        // Delete the user from the database
        $query = "DELETE FROM users WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(['id' => $userId]);

        echo "User deleted successfully.";
    else:
        echo "User ID not provided.";
    endif;
}

// Handle actions
if(isset($_GET['action'])):
    switch ($_GET['action']):
        case 'add':
            addUser();
            break;
        case 'edit':
            editUser();
            break;
        case 'delete':
            deleteUser();
            break;
        default:
            echo "Invalid action.";
            break;
    endswitch;
endif;

// Display all registered users
echo "<h2>Page Administration</h2>";
echo "<h3>All Registered Users:</h3>";
viewAllUsers();

// Add user form
echo "<h3>Add User:</h3>";
echo "<a href='admin.php?action=add'>Add User</a>";
?>

