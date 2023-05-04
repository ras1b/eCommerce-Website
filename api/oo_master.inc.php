<?php
// Include our HTML Page Class
require_once ("oo_page.inc.php");
include ("css/master.css");

class MasterPage
{

    // -------FIELD MEMBERS----------------------------------------
    private $_htmlpage;

    // Holds our Custom Instance of an HTML Page
    private $_dynamic_1;

    // Field Representing our Dynamic Content #1
    private $_dynamic_2;

    // Field Representing our Dynamic Content #2
    private $_dynamic_3;

    // Field Representing our Dynamic Content #3

    // -------CONSTRUCTORS-----------------------------------------
    function __construct($ptitle)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->_htmlpage = new HTMLPage($ptitle);
        $this->setPageDefaults();
        $this->setDynamicDefaults();
    }

    // -------GETTER/SETTER FUNCTIONS------------------------------
    public function getDynamic1()
    {
        return $this->_dynamic_1;
    }

    public function getDynamic2()
    {
        return $this->_dynamic_2;
    }

    public function getDynamic3()
    {
        return $this->_dynamic_3;
    }

    public function setDynamic1($phtml)
    {
        $this->_dynamic_1 = $phtml;
    }

    public function setDynamic2($phtml)
    {
        $this->_dynamic_2 = $phtml;
    }

    public function setDynamic3($phtml)
    {
        $this->_dynamic_3 = $phtml;
    }

    public function getPage(): HTMLPage
    {
        return $this->_htmlpage;
    }

    // -------PUBLIC FUNCTIONS-------------------------------------
    public function createPage()
    {
        // Create our Dynamic Injected Master Page
        $this->setMasterContent();
        // Return the HTML Page..
        return $this->_htmlpage->createPage();
    }

    public function renderPage()
    {
        // Create our Dynamic Injected Master Page
        $this->setMasterContent();
        // Echo the page immediately.
        $this->_htmlpage->renderPage();
    }

    public function addCSSFile($pcssfile)
    {
        $this->_htmlpage->addCSSFile($pcssfile);
    }

    public function addScriptFile($pjsfile)
    {
        $this->_htmlpage->addScriptFile($pjsfile);
    }

    // -------PRIVATE FUNCTIONS-----------------------------------
    private function setPageDefaults()
    {
        $this->_htmlpage->setMediaDirectory("css", "js", "fonts", "img", "");
        $this->addCSSFile("bootstrap.default.css");
        $this->addCSSFile("site.css");
        $this->addScriptFile("jquery-2.2.4.js");
        $this->addScriptFile("bootstrap.js");
        $this->addScriptFile("holder.js");
    }

    private function setDynamicDefaults()
    {
        $tcurryear = date("Y");
        // Set the Three Dynamic Points to Empty By Default.
        $this->_dynamic_1 = <<<JUMBO
        <h1></h1>
        <p class="lead"></p>
        JUMBO;
        $this->_dynamic_2 = "";
        $this->_dynamic_3 = <<<FOOTER
        <p>Mohammed Rasib Helal - LJMU &copy; {$tcurryear}</p>
        FOOTER;
    }

    private function setNavbar()
    {
        $html = '<ul class="nav navbar-nav navbar-right">';
        // echo $_SESSION['myuser'];
        if (isset($_SESSION['myuser']) && ! empty($_SESSION['myuser'])) {
            $html .= '<li><a>Welcome, ' . $_SESSION['myuser'] . '</a></li>';
            $html .= '<li><a href="myprofile.php"><span class="glyphicon glyphicon-log-out"></span> My Profile</a></li>';
            $html .= '<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
        } else if (basename($_SERVER['PHP_SELF']) != 'login.php') {
            // Check if the current page is not the login page
            $html .= '<li><a>Not logged in</a></li>';
            $html .= '<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';

            // error_log("Session Value: " . print_r($_SESSION['myuser'], true));
        }
        $html .= '</ul>';

        return $html;
    }

    private function setMasterContent()
    {
        $navbar = $this->setNavbar();

        $tmasterpage = <<<MASTER
            <div class="container">
                <nav class="navbar navbar-default">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand">My Website</a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="index.php">Home</a></li>
                            <li class="dropdown dropdown-left">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Products<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="products.php">» Smartphones</a></li>
                                </ul>
                            </li>
                        </ul>
                        $navbar
                    </div>
                </nav>
                <div class="col-lg-6">
                    {$this->_dynamic_2}
                </div>
                <footer class="footer">
                    {$this->_dynamic_3}
                </footer>
            </div>
        MASTER;

        $this->_htmlpage->setBodyContent($tmasterpage);
    }
}
?>