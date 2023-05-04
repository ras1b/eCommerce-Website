<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");

// Start session
session_start();

// Get users data from JSON file
$usersFile = 'data/json/users.json';
$users = json_decode(file_get_contents($usersFile), true);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    // Perform validation here
    if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
        // Set session variables
        $_SESSION['myuser'] = $username;

        // Set session variables if the username isn't empty
        if (! empty($username)) {
            $_SESSION['myuser'] = $username;
        }

        // Set cookie if 'remember me' checked
        if ($remember_me) {
            setcookie('myuser', $username, time() + (86400 * 30), "/"); // 30 days
        }

        // Redirect to home page
        header('Location: index.php');
        exit();
    } else {
        // Invalid login, redirect back to login page with error message
        header('Location: login.php?error=1');
        exit();
    }
}

// If the form wasn't submitted or there was an error, redirect back to login page
header('Location: login.php');
exit();
?>
