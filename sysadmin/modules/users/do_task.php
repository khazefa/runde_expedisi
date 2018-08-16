<?php
session_start();
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    require("../../../includes/constants.php");
    require_once("../../../includes/class.db.php");
    $database = DB::getInstance();

    $getpage = htmlspecialchars($_GET["page"], ENT_QUOTES, 'UTF-8');
    $getact = htmlspecialchars($_GET["act"], ENT_QUOTES, 'UTF-8');

    // Save data
    if ($getpage == "user-list" AND $getact == "save"){
        $funame = isset($_POST["funame"]) ? filter_var($_POST['funame'], FILTER_SANITIZE_STRING) : null;
        $fpass = isset($_POST["fpass"]) ? filter_var($_POST['fpass'], FILTER_SANITIZE_STRING) : null;
        $fname = isset($_POST["fname"]) ? filter_var($_POST['fname'], FILTER_SANITIZE_STRING) : null;
        $femail = isset($_POST["femail"]) ? filter_var($_POST['femail'], FILTER_SANITIZE_STRING) : null;
        
        $arrValue = array(
            'user_keyname' => $funame,
            'user_keypass' => md5($fpass),
            'user_fullname' => $fname,
            'user_email' => $femail,
            'level_id' => 2,
            'user_status' => 0,
        );
        $add_query = $database->insert( 'users', $arrValue );
        if( $add_query )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Update data
    elseif ($getpage == "user-list" AND $getact == "update"){
        $fid = isset($_POST["fid"]) ? filter_var($_POST['fid'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $funame = isset($_POST["funame"]) ? filter_var($_POST['funame'], FILTER_SANITIZE_STRING) : null;
        $fpass = isset($_POST["fpass"]) ? filter_var($_POST['fpass'], FILTER_SANITIZE_STRING) : null;
        $fname = isset($_POST["fname"]) ? filter_var($_POST['fname'], FILTER_SANITIZE_STRING) : null;
        $femail = isset($_POST["femail"]) ? filter_var($_POST['femail'], FILTER_SANITIZE_STRING) : null;
        $fstatus = isset($_POST["fstatus"]) ? filter_var($_POST['fstatus'], FILTER_SANITIZE_NUMBER_INT) : null;
        
        if(empty($fpass)){
            $update = array(
                'user_fullname' => $fname,
                'user_email' => $femail,
                'user_status' => $fstatus
            );
        }else{
            $update = array(
                'user_keypass' => md5($fpass),
                'user_fullname' => $fname,
                'user_email' => $femail,
                'user_status' => $fstatus
            );
        }
        
        //Add the WHERE clauses
        $where_clause = array(
            'user_keyname' => $funame
        );
        $updated = $database->update( 'users', $update, $where_clause, 1 );
        if( $updated )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Delete data
    elseif ($getpage == "user-list" AND $getact == "delete"){
        $key = htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8');
        $where_clause = array(
            'user_keyname' => $key
        );
        //Query delete
        $deleted = $database->delete( 'users', $where_clause);
        if( $deleted )
        {
            header('location:../../?page='.$getpage);
        }
    }
}
?>