<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");

// Read JSON data
function getDeviceData()
{
    $jsonData = file_get_contents("data/json/smartphones.json");
    $devices = json_decode($jsonData, true);
    return $devices;
}

// Find a device by ID
function findDeviceById($devices, $id)
{
    foreach ($devices as $device) {
        if ($device['id'] == $id) {
            return $device;
        }
    }
    return null;
}

// Check if user has already submitted a review
function userHasSubmittedReview($device, $username)
{
    if (! empty($device['reviews'])) {
        foreach ($device['reviews'] as $review) {
            if ($review['username'] == $username) {
                return true;
            }
        }
    }
    return false;
}

// Generate review form
function generateReviewForm($device)
{
    isset($_SESSION['myuser']) ? $_SESSION['myuser'] : ""; // Check if username is set in the session
    $form = <<<FORM
        <h1>Create a review</h1>
        <img src="{$device['image_href']}" alt="{$device['devicename']}">
        <h5>{$device['devicename']}</h5>
        <form method="POST" action="submit_review.php">
            <input type="hidden" name="device_id" value="{$device['id']}">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="{$_SESSION['myuser']}" readonly>
            <br>
            <label for="review_title">Review Title:</label>
            <input type="text" id="review_title" name="review_title" required>
            <br>
            <label for="review_text">Review Text:</label>
            <textarea id="review_text" name="review_text" required></textarea>
            <br>
            <label for="rating">Rating (out of 10):</label>
            <input type="number" id="rating" name="rating" min="0" max="10" step="0.1" required>
            <br>
            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
        </form>
    FORM;
    return $form;
}

// ----PAGE GENERATION LOGIC---------------------------
function createPage($device)
{
    $username = isset($_SESSION['myuser']) ? $_SESSION['myuser'] : "";

    if (userHasSubmittedReview($device, $username)) {
        $tcontent = "<p>You have already submitted a review for this device.</p>";
    } else {
        $tcontent = generateReviewForm($device);
    }

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
session_start();
// Get the device ID from the URL (if 'id' parameter is provided)
$deviceId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($deviceId !== null) {
    // Get the devices data
    $devices = getDeviceData();

    // Find the device by ID
    $device = findDeviceById($devices, $deviceId);

    if ($device !== null) {
        // Generate the page content
        $tpagecontent = createPage($device);
    } else {
        $tpagecontent = "<p>Device not found.</p>";
    }
}
// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Review Page");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();

?>