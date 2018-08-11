<?php
session_start();
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    require("../../includes/constants.php");
    require_once("../../includes/class.db.php");
    $database = DB::getInstance();

    $getpage = htmlspecialchars($_GET["page"], ENT_QUOTES, 'UTF-8');
    $getact = htmlspecialchars($_GET["act"], ENT_QUOTES, 'UTF-8');

    // Save data
    if ($getpage == "data-biaya-kirim" AND $getact == "save"){
        $fekspedisi = isset($_POST["fekspedisi"]) ? filter_var($_POST['fekspedisi'], FILTER_SANITIZE_NUMBER_INT) : 0;
//        $fprovinsi = isset($_POST["fprovinsi"]) ? filter_var($_POST['fprovinsi'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fkabupaten = isset($_POST["fkabupaten"]) ? filter_var($_POST['fkabupaten'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fudara = isset($_POST["fudara"]) ? filter_var($_POST['fudara'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fdarat = isset($_POST["fdarat"]) ? filter_var($_POST['fdarat'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $flaut = isset($_POST["flaut"]) ? filter_var($_POST['flaut'], FILTER_SANITIZE_NUMBER_INT) : 0;
        
        $arrValue = array(
            'id_kabupaten' => $fkabupaten,
            'ekspedisi_id' => $fekspedisi,
            'biaya_via_udara' => $fudara,
            'biaya_via_darat' => $fdarat,
            'biaya_via_laut' => $flaut
        );
        $add_query = $database->insert( 'biaya_kirim', $arrValue );
        if( $add_query )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Update data
    elseif ($getpage == "data-biaya-kirim" AND $getact == "update"){
        $fid = isset($_POST["fid"]) ? filter_var($_POST['fid'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fekspedisi = isset($_POST["fekspedisi"]) ? filter_var($_POST['fekspedisi'], FILTER_SANITIZE_NUMBER_INT) : 0;
//        $fprovinsi = isset($_POST["fprovinsi"]) ? filter_var($_POST['fprovinsi'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fkabupaten = isset($_POST["fkabupaten"]) ? filter_var($_POST['fkabupaten'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fudara = isset($_POST["fudara"]) ? filter_var($_POST['fudara'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fdarat = isset($_POST["fdarat"]) ? filter_var($_POST['fdarat'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $flaut = isset($_POST["flaut"]) ? filter_var($_POST['flaut'], FILTER_SANITIZE_NUMBER_INT) : 0;
        
        $update = array(
//            'id_kabupaten' => $fkabupaten,
//            'ekspedisi_id' => $fekspedisi,
            'biaya_via_udara' => $fudara,
            'biaya_via_darat' => $fdarat,
            'biaya_via_laut' => $flaut
        );
        //Add the WHERE clauses
        $where_clause = array(
            'biaya_id' => $fid
        );
        $updated = $database->update( 'biaya_kirim', $update, $where_clause, 1 );
        if( $updated )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Delete data
    elseif ($getpage == "data-biaya-kirim" AND $getact == "delete"){
        $key = htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8');
        $where_clause = array(
            'biaya_id' => $key
        );
        //Query delete
        $deleted = $database->delete( 'biaya_kirim', $where_clause);
        if( $deleted )
        {
            header('location:../../?page='.$getpage);
        }
    }
}
?>