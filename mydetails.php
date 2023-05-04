<?php

// MY ADDITIONAL FEATURE / VIEW

// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
session_start();

// echo $_SESSION["myuser"];
function createPage()
{
    // Decode the users.json file into a PHP associative array
    $users = json_decode(file_get_contents("data/json/users.json"), true);

    // Retrieve the username of the current user from the session variable
    $username = $_SESSION["myuser"];

    // Get the account details for the current user from the users array
    $details = $users[$username];

    // Check if the user submitted a form to update their account details
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Update the fields in the $details array based on the user's input
        $details["email"] = $_POST["email"];
        $details["first_name"] = $_POST["first_name"];
        $details["last_name"] = $_POST["last_name"];
        $details["phone_number"] = $_POST["phone_number"];

        // Update the users array with the new account details
        $users[$username] = $details;

        // Encode the updated users array as JSON and save it to the file
        file_put_contents("data/json/users.json", json_encode($users, JSON_PRETTY_PRINT));

        // Display a message confirming that the account details were updated
        $message = "<p>Your account details have been updated.</p>";
    } else {
        $message = "";
    }

    // Display the account details in the HTML content with input fields for each field
    $tcontent = <<<PAGE
        <h1>My Account Details</h1>
        $message
        <form method="post">
            <p><strong>Email:</strong> <input type="text" name="email" value="{$details["email"]}" /></p>
            <p><strong>First Name:</strong> <input type="text" name="first_name" value="{$details["first_name"]}" /></p>
            <p><strong>Last Name:</strong> <input type="text" name="last_name" value="{$details["last_name"]}" /></p>
            <p><strong>Phone Number:</strong> <input type="text" name="phone_number" value="{$details["phone_number"]}" /></p>
            <input type="submit" value="Update" />
        </form>
        <p><i><u> This is my additional application view </u></i></p>
    PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage();

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("My Details");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();
?>
