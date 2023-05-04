<?php
// ----INCLUDE APIS------------------------------------
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

// Get reviews by device ID
function getReviewsByDeviceId($deviceId)
{
    $devices = getDeviceData();
    $device = findDeviceById($devices, $deviceId);

    if ($device !== null && isset($device["consumer_info"]["user_reviews"])) {
        return $device["consumer_info"]["user_reviews"];
    }

    return [];
}

// Check if user has already submitted a review
function userHasSubmittedReview($deviceId, $username)
{
    $reviews = getReviewsByDeviceId($deviceId);
    if (! empty($reviews)) {
        foreach ($reviews as $review) {
            if ($review['username'] == $username) {
                return true;
            }
        }
    }
    return false;
}

// ----PAGE GENERATION LOGIC---------------------------
function createPage($message)
{
    $tcontent = <<<HTML
        <p>{$message}</p>
        <a href="index.php" class="button">Return to Home</a>
    HTML;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
session_start();

// Get the device ID and review data from the submitted form
$deviceId = isset($_POST['device_id']) ? intval($_POST['device_id']) : null;
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$reviewTitle = isset($_POST['review_title']) ? trim($_POST['review_title']) : '';
$reviewText = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';
$rating = isset($_POST['rating']) ? floatval($_POST['rating']) : '';

if ($deviceId !== null && ! empty($username) && ! empty($reviewTitle) && ! empty($reviewText) && $rating >= 0 && $rating <= 10) {
    $devices = getDeviceData();
    $device = findDeviceById($devices, $deviceId);

    if ($device !== null) {
        $reviews = isset($device["consumer_info"]["user_reviews"]) ? $device["consumer_info"]["user_reviews"] : [];

        // Check if user has already submitted a review
        if (userHasSubmittedReview($deviceId, $username)) {
            $tcontent = createPage("You have already submitted a review for this device.");
            $reviewPage = new MasterPage("Review Page");
            $reviewPage->setDynamic2($tcontent);
            $reviewPage->renderPage();
            exit();
        } else {
            // Add the new review to the device's reviews
            $newReview = [
                "username" => $username,
                "review_title" => $reviewTitle,
                "review_text" => $reviewText,
                "rating" => $rating,
                "date" => date("Y-m-d")
            ];
            $reviews[] = $newReview;

            // Update the device's reviews and rating in the devices array
            foreach ($devices as &$d) {
                if ($d["id"] == $deviceId) {
                    $d["consumer_info"]["user_reviews"] = $reviews;
                    break;
                }
            }

            // Save the updated devices array to the JSON file
            $jsonData = json_encode($devices, JSON_PRETTY_PRINT);
            file_put_contents("data/json/smartphones.json", $jsonData);

            // Redirect the user to the device's page
            header("Location: smartphone.php?id=$deviceId");
            exit();
        }
    } else {
        $tcontent = createPage("Device not found.");
        $reviewPage = new MasterPage("Review Page");
        $reviewPage->setDynamic2($tcontent);
        $reviewPage->renderPage();
        exit();
    }
} else {
    $tcontent = createPage("Invalid review data. Please fill in all fields and provide a rating between 0 and 10.");
    $reviewPage = new MasterPage("Review Page");
    $reviewPage->setDynamic2($tcontent);
    $reviewPage->renderPage();
    exit();
}
?>