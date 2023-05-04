<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    // Get the latest version of iOS
    $latest_ios = "iOS 16";

    // Create content for iOS section
    $ios_content = <<<IOS
        <h2>What is iOS?</h2>
        <p>iOS is a mobile operating system developed by Apple Inc. It is the operating system that powers many of the company's mobile devices, including the iPhone, iPad, and iPod Touch.</p>
        
        <h2>Features of iOS 15</h2>
        <ul>
            <li>FaceTime improvements</li>
            <li>Focus mode</li>
            <li>Live Text</li>
            <li>Notification summary</li>
            <li>Maps enhancements</li>
        </ul>
        
        <h2>What's new in iOS 16?</h2>
        <ul>
            <li>Redesigned home screen</li>
            <li>Widgets on iPad home screen</li>
            <li>New lock screen and control center</li>
            <li>Improved Siri functionality</li>
            <li>New privacy features</li>
        </ul>
        
        <h2>Changes from iOS 15 to iOS 16</h2>
        <iframe width="560" height="315" src="vid/ios/16.mp4" frameborder="0" allowfullscreen autoplay="0"></iframe>
    IOS;

    // Create the page content
    $tcontent = <<<PAGE
        <div class="jumbotron">
            <h1>Welcome to our website!</h1>
            <p class="lead">The latest version of iOS is $latest_ios.</p>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                $ios_content
            </div>
            <div class="col-md-4">
                <h2>News</h2>
                <p>Check out the latest blog post for news and updates on iOS.</p>
                <p><a class="btn btn-default" href="https://www.apple.com/ios/" role="button">View details &raquo;</a></p>
            </div>
        </div>
    PAGE;

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage();

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("iOS Infomation");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();

?>