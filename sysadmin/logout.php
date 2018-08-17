<?php
    session_start();
    $_SESSION = [];
    session_destroy();
    require_once "../includes/constants.php";
    header('location:'.$baseurl);
?>