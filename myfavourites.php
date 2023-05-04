<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");
include ("css/myfavourites.css");

session_start();

// Get the favorited device from the URL parameter
$favorited_device = isset($_GET["favorited_device"]) ? $_GET["favorited_device"] : '';
$remove_favorited_device = isset($_GET["remove_favorited_device"]) ? $_GET["remove_favorited_device"] : '';

// If the user is logged in and a device has been favorited, save it to the favorites JSON file
if ($favorited_device && isset($_SESSION['myuser'])) {
    $username = $_SESSION['myuser'];
    $favorites_file = 'data/json/favorites.json';
    $favorites_data = file_get_contents($favorites_file);
    $favorites = json_decode($favorites_data, true);

    if (! isset($favorites[$username])) {
        $favorites[$username] = array();
    }

    if (! in_array($favorited_device, $favorites[$username])) {
        array_push($favorites[$username], $favorited_device);
    }

    $favorites_data = json_encode($favorites, JSON_PRETTY_PRINT);
    file_put_contents($favorites_file, $favorites_data);
}

// If the user is logged in and a favorited device has been removed, update the favorites JSON file
if ($remove_favorited_device && isset($_SESSION['myuser'])) {
    $username = $_SESSION['myuser'];
    $favorites_file = 'data/json/favorites.json';
    $favorites_data = file_get_contents($favorites_file);
    $favorites = json_decode($favorites_data, true);

    if (isset($favorites[$username])) {
        $index = array_search($remove_favorited_device, $favorites[$username]);
        if ($index !== false) {
            array_splice($favorites[$username], $index, 1);

            // If the user has removed all of their favorite devices, remove the associated object entirely
            if (empty($favorites[$username])) {
                unset($favorites[$username]);
            }

            $favorites_data = json_encode($favorites, JSON_PRETTY_PRINT);
            file_put_contents($favorites_file, $favorites_data);
        }
    }
}

// Construct the page content
if (isset($_SESSION['myuser'])) {
    $username = $_SESSION['myuser'];
    $favorites_file = 'data/json/favorites.json';
    $favorites_data = file_get_contents($favorites_file);
    $favorites = json_decode($favorites_data, true);

    if (isset($favorites[$username])) {
        $tcontent = '<div class="gray-bar">';
        $tcontent .= '<h2>My Favourites</h2>';
        $tcontent .= '</div>';

        foreach ($favorites[$username] as $device) {
            $tcontent .= '<div class="favorited-device">';
            $tcontent .= '<h3>' . $device . '</h3>';
            $tcontent .= '<a href="?remove_favorited_device=' . urlencode($device) . '">Remove from Favourites</a>';
            $tcontent .= '</div>';
        }
    } else {
        $tcontent = '<h2>No device has been favorited yet.</h2>';
    }
} else {
    $tcontent = '<h2>Please log in to view your favorites.</h2>';
}

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("My Favourites");
$tindexpage->setDynamic2($tcontent);
$tindexpage->renderPage();

?>
