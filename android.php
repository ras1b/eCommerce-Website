<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    // Get the latest version of Android
    $latest_android = "Android 12";

    // Create content for Android section
    $android_content = <<<ANDROID
        <h2>What is Android?</h2>
        <p>Android is a mobile operating system developed by Google. It is based on the Linux kernel and is designed primarily for touchscreen mobile devices such as smartphones and tablets.</p>
        
        <h2>Features of Android 10</h2>
        <ul>
            <li>Dark theme support</li>
            <li>Gesture navigation</li>
            <li>Live caption</li>
            <li>Smart reply</li>
            <li>Privacy controls</li>
        </ul>
        
        <h2>What's new in Android 11?</h2>
        <ul>
            <li>Built-in screen recording</li>
            <li>New media controls</li>
            <li>One-time permissions</li>
            <li>Wireless Android Auto</li>
            <li>Chat bubbles</li>
        </ul>
        
        <h2>Changes from Android 10 to Android 11</h2>
        <iframe width="560" height="315" src="vid/android/11.mp4" frameborder="0" allowfullscreen autoplay="0"></iframe>
    ANDROID;

    // Create the page content
    $tcontent = <<<PAGE
        <div class="jumbotron">
            <h1>Welcome to our website!</h1>
            <p class="lead">The latest version of Android is $latest_android.</p>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                $android_content
            </div>
            <div class="col-md-4">
                <h2>News</h2>
                <p>Check out the latest blog post for news and updates on Android .</p>
                <p><a class="btn btn-default" href="https://www.android.com/intl/en_uk/" role="button">View details &raquo;</a></p>
            </div>
        </div>
    PAGE;

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage();

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Android Infomation");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();

?>

