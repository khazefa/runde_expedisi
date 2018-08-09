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
    if ($getpage == "courier-list" AND $getact == "save"){
        $fname = isset($_POST["fname"]) ? filter_var($_POST['fname'], FILTER_SANITIZE_STRING) : null;
        $fdest = isset($_POST["fdest"]) ? filter_var($_POST['fdest'], FILTER_SANITIZE_STRING) : null;
        $fcost = isset($_POST["fcost"]) ? filter_var($_POST['fcost'], FILTER_SANITIZE_NUMBER_INT) : null;
        
        $arrValue = array(
            'shipping_courier' => $fname,
            'shipping_dest' => $fdest,
            'shipping_cost' => $fcost
        );
        $add_query = $database->insert( 'shipping', $arrValue );
        if( $add_query )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Update data
    elseif ($getpage == "courier-list" AND $getact == "update"){
        $fid = isset($_POST["fid"]) ? filter_var($_POST['fid'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fcost = isset($_POST["fcost"]) ? filter_var($_POST['fcost'], FILTER_SANITIZE_NUMBER_INT) : null;
        
        $update = array(
            'shipping_cost' => $fcost
        );
        //Add the WHERE clauses
        $where_clause = array(
            'shipping_id' => $fid
        );
        $updated = $database->update( 'shipping', $update, $where_clause, 1 );
        if( $updated )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Delete data
    elseif ($getpage == "courier-list" AND $getact == "delete"){
        $key = htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8');
        $where_clause = array(
            'shipping_id' => $key
        );
        //Query delete
        $deleted = $database->delete( 'shipping', $where_clause);
        if( $deleted )
        {
            header('location:../../?page='.$getpage);
        }
    }

}
?>