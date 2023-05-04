<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");
include ("css/index.css");

// ----PAGE GENERATION LOGIC---------------------------

// var_dump($_SESSION);
function createPage()
{
    // Read the JSON data from the file
    $devicesData = file_get_contents('data/json/smartphones.json');
    // Decode the JSON data into an array
    $devices = json_decode($devicesData, true);

    // Generate HTML code for each device
    $devicesHtml = '';
    foreach ($devices as $device) {
        $devicesHtml .= '<div class="product-container">';
        $devicesHtml .= '<h3>' . $device['devicename'] . '</h3>';
        $devicesHtml .= '<img src="' . $device['image_href'] . '" alt="' . $device['devicename'] . '">';
        $devicesHtml .= '<p>' . $device['device_summary'] . '</p>';
        $devicesHtml .= '<a href="smartphone.php?id=' . $device['id'] . '" class="btn btn-primary mt-5">View more..</a>';
        $devicesHtml .= '</div>';
    }

    $tcontent = <<<PAGE
        <div class="welcome">
            <h1>Welcome to my Store!</h1>
            <p>Check out the latest <u><b>Featured Products!</b></u></p>
        </div>
        <div class=samsung><h2><u>Samsung Galaxy S21 Release</u></h2><iframe width="560" height="315" src="vid/featured/s21.mp4" frameborder="0" allowfullscreen autoplay="0"></iframe></div>
        <div class=apple><h2><u>iPhone 14 Pro Release</u></h2><iframe width="560" height="315" src="vid/featured/14pro.mp4" frameborder="0" allowfullscreen autoplay="0"></iframe></div>
        <div class=motorola><h2><u>Motorola G9 Play Release</u></h2><iframe width="560" height="315" src="vid/featured/g9play.mp4" frameborder="0" allowfullscreen autoplay="0"></iframe></div>
        <div class="product-flex-container">
            $devicesHtml
        </div>
    PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
session_start();
// Build up our Dynamic Content Items.
$tpagetitle = "Home Page";
$tpagelead = "";
$tpagecontent = createPage();
$tpagefooter = "";

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
// Set the Three Dynamic Areas (1 and 3 have defaults)
if (! empty($tpagelead)) {
    $tpage->setDynamic1($tpagelead);
}
if (! empty($tpagecontent)) {
    $tpage->setDynamic2('<div class="content">' . $tpagecontent . '</div>');
}
if (! empty($tpagefooter)) {
    $tpage->setDynamic3('<div class="footer">' . $tpagefooter . '</div>');
}

// Return the Dynamic Page to the user.
$tpage->renderPage();
?>
