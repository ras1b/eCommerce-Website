<?php
session_start();

// Unset the session variable
unset($_SESSION['myuser']);

// Destroy the session
session_destroy();

// Remove the cookie if it's set
if (isset($_COOKIE['myuser'])) {
    setcookie('myuser', '', time() - 3600, '/');
}

// Redirect to the home page
header('Location: index.php');
exit();
?>
