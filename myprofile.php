<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");
include ('css/myprofile.css');

// ----PAGE GENERATION LOGIC---------------------------
session_start();

function createPage()
{
    // Get the username from the session, if available
    $username = isset($_SESSION['myuser']) ? $_SESSION['myuser'] : '';

    // Construct the welcome message and box containers
    $tcontent = '<h1>Welcome, ' . $username . '!</h1>';
    $tcontent .= '<div class="box-container">';
    $tcontent .= '<div class="box-wrapper">';
    $tcontent .= '<a href="#orders" class="box" style="background-image: url(img/myprofile/parcel.jpeg)">';
    $tcontent .= '<h3>My Orders</h3>';
    $tcontent .= '</a>';
    $tcontent .= '</div>';
    $tcontent .= '<div class="box-wrapper">';
    $tcontent .= '<a href="myfavourites.php" class="box" style="background-image: url(img/myprofile/favourites.jpg)">';
    $tcontent .= '<h3>My Favourites</h3>';
    $tcontent .= '</a>';
    $tcontent .= '</div>';
    $tcontent .= '<div class="box-wrapper">';
    $tcontent .= '<a href="mydetails.php" class="box" style="background-image: url(img/myprofile/details.jpeg)">';
    $tcontent .= '<h3>My Details</h3>';
    $tcontent .= '</a>';
    $tcontent .= '</div>';
    $tcontent .= '</div>';

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage();

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("My Profile");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();
?>
