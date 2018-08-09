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
    if ($getpage == "bank-acc" AND $getact == "save"){
        $fbank = isset($_POST["fbank"]) ? filter_var($_POST['fbank'], FILTER_SANITIZE_STRING) : null;
        $fname = isset($_POST["fname"]) ? filter_var($_POST['fname'], FILTER_SANITIZE_STRING) : null;
        $fno = isset($_POST["fno"]) ? filter_var($_POST['fno'], FILTER_SANITIZE_STRING) : null;
        
        $arrValue = array(
            'bank_acc_no' => $fno,
            'bank_acc_name' => $fname,
            'bank_acc_bank' => $fbank
        );
        $add_query = $database->insert( 'bank_acc', $arrValue );
        if( $add_query )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Update data
    elseif ($getpage == "bank-acc" AND $getact == "update"){
        $fid = isset($_POST["fid"]) ? filter_var($_POST['fid'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fbank = isset($_POST["fbank"]) ? filter_var($_POST['fbank'], FILTER_SANITIZE_STRING) : null;
        $fname = isset($_POST["fname"]) ? filter_var($_POST['fname'], FILTER_SANITIZE_STRING) : null;
        $fno = isset($_POST["fno"]) ? filter_var($_POST['fno'], FILTER_SANITIZE_STRING) : null;
        
        $update = array(
            'bank_acc_no' => $fno,
            'bank_acc_name' => $fname,
            'bank_acc_bank' => $fbank
        );
        //Add the WHERE clauses
        $where_clause = array(
            'bank_acc_id' => $fid
        );
        $updated = $database->update( 'bank_acc', $update, $where_clause, 1 );
        if( $updated )
        {
            header('location:../../?page='.$getpage);
        }
    }

}
?>