<?php
session_start();
require("../includes/constants.php");

$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    require('../includes/class.db.php');
    $database = DB::getInstance();
    require("../includes/global_helper.php");
    require("../includes/common_helper.php");
    require("../includes/auto_number_helper.php");

    include("template/v_header.php");
    include("template/v_nav.php");
    include("template/v_sidebar.php");
    include("template/v_content.php");
    include("template/v_footer.php");
}
?>