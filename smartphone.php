<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");
include ("css/smartphone.css");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    $id = 0;
    $tcontent = "";

    // Check if the id is set in the URL query parameter
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        // Display an error message and exit
        $tpagecontent = '<p>Invalid device ID.</p>';
        $tpagecontent .= '<a href="index.php"><button>Return to Home</button></a>';
        $tpagecontent .= '<a href="smartphone.php?id=0"><button>ID 0</button></a>';
        $tpagecontent .= '<a href="smartphone.php?id=1"><button>ID 1</button></a>';
        $tindexpage = new MasterPage("Error Page");
        $tindexpage->setDynamic2($tpagecontent);
        $tindexpage->renderPage();
        exit();
    }

    // Check if the id is less 1 and will give the option of return and spit an error
    if ($id < 1) {
        // Display an error message and exit
        $tpagecontent = '<p>Invalid device ID.</p>';
        $tpagecontent .= '<a href="index.php"><button>Return to Home</button></a>';
        $tpagecontent .= '<a href="smartphone.php?id=0"><button>ID 0</button></a>';
        $tpagecontent .= '<a href="smartphone.php?id=1"><button>ID 1</button></a>';
        $tindexpage = new MasterPage("Error Page");
        $tindexpage->setDynamic2($tpagecontent);
        $tindexpage->renderPage();
        exit();
    }

    // echo 'getting file';
    // Check if the smartphones.json file exists
    if (file_exists("data/json/smartphones.json")) {
        // echo 'file exists';
        // Read the device data from the JSON file
        $json_data = file_get_contents("data/json/smartphones.json");
        if ($json_data) {
            $json_data = json_decode($json_data, true);
            if ($json_data) {
                // echo $json_data;
                $tcontent = '<p>Invalid device ID.</p>';
                $tcontent .= '<a href="index.php"><button>Return to Home</button></a>';
                $tcontent .= '<a href="smartphone.php?id=0"><button>ID 0</button></a>';
                $tcontent .= '<a href="smartphone.php?id=1"><button>ID 1</button></a>';
                foreach ($json_data as $device) {
                    if ($device['id'] >= 1 && $id == $device['id']) {
                        $tcontent = '<h1>' . $device['devicename'] . '</h1>';
                        $tcontent .= '<img src="' . $device['image_href'] . '" alt="' . $device['devicename'] . '">';
                        $tcontent .= '<ul>';
                        $tcontent .= '<h4>Manufacturer: ' . $device['manufacturer'] . '</h4>';
                        $tcontent .= '<h4><i><u><b>Price:' . ' ' . $device['price'] . '</b></u></i></h4>';
                        $tcontent .= '<h5><b>Release Date:</b> ' . $device['release_date'] . '</h5>';
                        $tcontent .= '<h5><b>Operating System:</b> ' . $device['os'] . '</h5>';
                        session_start();
                        // var_dump($_SESSION);
                        if (isset($_SESSION['myuser'])) { // Check if user is logged in
                            $tcontent .= '<div class="review"><a href="review.php?id=' . $id . '" class="btn btn-danger mt-3">Create a review</a></div>'; // Display the button if user is logged in
                        }
                        $tcontent .= '<a class="favorite" href="myfavourites.php?favorited_device=' . urlencode($device["devicename"]) . '"><h5>Add to my Favorites</h5> ★</a>';
                        $os_url = '';
                        $os_text = '';
                        if (stripos($device['os'], 'Android') === 0) {
                            $os_url = 'android.php';
                            $os_text = 'Android';
                        } else if (stripos($device['os'], 'iOS') === 0) {
                            $os_url = 'ios.php';
                            $os_text = 'iOS';
                        }
                        echo '<div class="os"><a href="' . $os_url . '" class="btn btn-secondary mt-5"><u> » Learn more about ' . $os_text . ' Operating System</a></u></div>';
                        $tcontent .= '<div class="buynow"><a href="#" class="btn btn-danger mt-3">Buy now!</a></div>';
                        $tcontent .= '<li>Display: ' . $device['specs']['display'] . '</li>';
                        $tcontent .= '<li>Camera: ' . $device['specs']['camera'] . '</li>';
                        $tcontent .= '<li>Storage: ' . $device['specs']['storage'] . '</li>';
                        $tcontent .= '<li>Memory: ' . $device['specs']['memory'] . '</li>';
                        $tcontent .= '<li>CPU Cores: ' . $device['specs']['cpu_cores'] . '</li>';
                        $tcontent .= '<li>Weight: ' . $device['specs']['weight'] . '</li>';
                        $tcontent .= '<li>Dimensions: ' . $device['specs']['dimensions'] . '</li>';
                        $tcontent .= '<li>Colors Available: ' . strval($device['specs']['colors_available']) . '</li>';
                        $tcontent .= '</ul>';
                        $tcontent .= '<h5><b>Device Description:</b> ' . $device['device_description'] . '</h5>';
                        $tcontent .= '<h1><u>Consumer Infomation:</u></h1>';
                        $tcontent .= '<h2>Retailers:</h2>';
                        $tcontent .= '<ul>';
                        foreach ($device['consumer_info']['retailers'] as $retailer) {
                            $tcontent .= '<li><b>' . $retailer['name'] . '</b>: <a href="' . $retailer['link'] . '">' . $retailer['link'] . '</a></li>';
                        }
                        $tcontent .= '<h2>External Reviews:</h2>';
                        $tcontent .= '<ul>';
                        foreach ($device['consumer_info']['official_reviews'] as $review) {
                            $tcontent .= '<li>' . $review['name'] . ': <a href="' . $review['link'] . '">' . $review['link'] . '</a></li>';
                        }
                        $tcontent .= '</ul>';

                        $tcontent .= '<h2>User Reviews:</h2>';
                        if (empty($device['consumer_info']['user_reviews'])) {
                            $tcontent .= '<p>No user reviews found.</p>';
                        } else {
                            $tcontent .= '<ul>';
                            foreach ($device['consumer_info']['user_reviews'] as $review) {
                                $tcontent .= '<b><li>' . $review['review_title'] . ' (' . $review['date'] . '</b>) </li>';
                                $tcontent .= '<i>"' . $review['review_text'] . '"</i> <b> ~ ' . $review['username'] . ' ' . 'rated this ' . $review['rating'] . ' stars</b>';
                            }
                            $tcontent .= '</ul>';
                        }
                        // exit();
                    }
                }
            } else {
                error_log("Failed to decode json...");
            }
        } else {
            error_log("Failed to fetch json...");
        }
    }
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage();

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Device Page");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();

?>
