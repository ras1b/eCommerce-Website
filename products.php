<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");
include ("css/products.css");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    // Read the JSON data
    $json_data = file_get_contents('data/json/smartphones.json');
    $products = json_decode($json_data, true);

    // Sort the products by average rating in descending order
    usort($products, function ($a, $b) {
        return getAverageRating($b["consumer_info"]["user_reviews"]) <=> getAverageRating($a["consumer_info"]["user_reviews"]);
    });

    // Initialize the content variable
    $tcontent = '<div class="product-container">';

    // Loop through each product and create a sub container
    foreach ($products as $product) {
        $tcontent .= '<div class="product">';
        $tcontent .= '<img src="' . $product["image_href"] . '">';
        $tcontent .= '<h3>' . $product["devicename"] . '</h3>';
        $tcontent .= '<p>Release Date: ' . $product["release_date"] . '</p>';
        $tcontent .= '<p><b>Summary:</b> <i>' . ' ' . $product["device_summary"] . '</i></p>';
        $tcontent .= '<p><b>Average User Rating:</b> ' . getAverageRating($product["consumer_info"]["user_reviews"]) . '</p>';
        $tcontent .= '<p><b>Store Rating:</b> 5.0</p>';
        $tcontent .= '<h3>' . $product["price"] . '</h3>';
        $tcontent .= '<button class="view-device" onclick="window.location.href=\'smartphone.php?id=' . $product["id"] . '\'">View Device</button>';
        $tcontent .= '<a class="favorite" href="myfavourites.php?favorited_device=' . urlencode($product["devicename"]) . '">★</a>';
        $tcontent .= '</div>';
    }

    // Close the container
    $tcontent .= '</div>';

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage();

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Products Page");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();

?>
