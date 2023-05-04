<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");
include ("css/login.css");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    $error = '';
    $usersFile = 'data/json/users.json';
    $users = json_decode(file_get_contents($usersFile), true);

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
            $error = 'Invalid username or password.';
        }
    }

    $tcontent = <<<PAGE
    <h1>Log In</h1>
    <form method="post" action="app_entry.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="remember_me">Remember me:</label>
        <input type="checkbox" id="remember_me" name="remember_me"><br><br>
        
        <input type="submit" value="Log In">
        <p style="color:red;">$error</p>
    </form>
    
    <p>Don't have an account? <a href="signup.php">Create an account</a>.</p>
    PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage();

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Login Page");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();

?>
