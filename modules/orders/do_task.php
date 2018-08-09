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

    // Update data
    if ($getpage == "customer-orders" AND $getact == "update"){
        /**
         * kurang fitur update stok aktual produk jika status pemesanan sudah komplit
         **/
        $fid = isset($_POST["fid"]) ? filter_var($_POST['fid'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fkey = isset($_POST["fkey"]) ? filter_var($_POST['fkey'], FILTER_SANITIZE_STRING) : null;
        $fstatus = isset($_POST["fstatus"]) ? filter_var($_POST['fstatus'], FILTER_SANITIZE_STRING) : null;
        
        $update = array(
            'order_status' => $fstatus
        );
        //Add the WHERE clauses
        $where_clause = array(
            'order_id' => $fid
        );
        $updated = $database->update( 'orders', $update, $where_clause, 1 );
        if( $updated )
        {
            header('location:../../?page='.$getpage);
        }
    }

}
?>