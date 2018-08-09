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
    if ($getpage == "data-principal" AND $getact == "save"){
        $fkode = isset($_POST["fkode"]) ? filter_var($_POST['fkode'], FILTER_SANITIZE_STRING) : null;
        $fnama = isset($_POST["fnama"]) ? filter_var($_POST['fnama'], FILTER_SANITIZE_STRING) : null;
        $falias = isset($_POST["falias"]) ? filter_var($_POST['falias'], FILTER_SANITIZE_STRING) : null;
        
        $arrValue = array(
            'principal_kode' => $fkode,
            'principal_nama' => $fnama,
            'principal_alias' => $falias
        );
        $add_query = $database->insert( 'principal', $arrValue );
        if( $add_query )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Update data
    elseif ($getpage == "data-principal" AND $getact == "update"){
        $fid = isset($_POST["fid"]) ? filter_var($_POST['fid'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fnama = isset($_POST["fnama"]) ? filter_var($_POST['fnama'], FILTER_SANITIZE_STRING) : null;
        $falias = isset($_POST["falias"]) ? filter_var($_POST['falias'], FILTER_SANITIZE_STRING) : null;
        
        $update = array(
            'principal_nama' => $fnama,
            'principal_alias' => $falias
        );
        //Add the WHERE clauses
        $where_clause = array(
            'principal_id' => $fid
        );
        $updated = $database->update( 'principal', $update, $where_clause, 1 );
        if( $updated )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Delete data
    elseif ($getpage == "data-principal" AND $getact == "delete"){
        $key = htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8');
        $where_clause = array(
            'principal_id' => $key
        );
        //Query delete
        $deleted = $database->delete( 'principal', $where_clause);
        if( $deleted )
        {
            header('location:../../?page='.$getpage);
        }
    }
}
?>