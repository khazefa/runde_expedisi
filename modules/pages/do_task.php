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
    if ($getpage == "list-pages" AND $getact == "save"){
        $ftitle = isset($_POST["ftitle"]) ? filter_var($_POST['ftitle'], FILTER_SANITIZE_STRING) : null;
//        $fcontent = isset($_POST["fcontent"]) ? filter_var($_POST['fcontent'], FILTER_SANITIZE_STRING) : null;
        $fcontent = isset($_POST["fcontent"]) ? $_POST['fcontent'] : null;
        $fpublish = isset($_POST["fpublish"]) ? filter_var($_POST['fpublish'], FILTER_SANITIZE_STRING) : null;
        
        $fslug = strtolower(str_replace(" ", "-", $ftitle));
        $arrValue = array(
            'pg_title' => $ftitle,
            'pg_slug' => $fslug,
            'pg_content' => $fcontent,
            'pg_publish' => $fpublish
        );
        $add_query = $database->insert( 'site_pages', $arrValue );
        if( $add_query )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Update data
    elseif ($getpage == "list-pages" AND $getact == "update"){
        $fid = isset($_POST["fid"]) ? filter_var($_POST['fid'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $ftitle = isset($_POST["ftitle"]) ? filter_var($_POST['ftitle'], FILTER_SANITIZE_STRING) : null;
        $fslug = isset($_POST["fslug"]) ? filter_var($_POST['fslug'], FILTER_SANITIZE_STRING) : null;
//        $fcontent = isset($_POST["fcontent"]) ? filter_var($_POST['fcontent'], FILTER_SANITIZE_STRING) : null;
        $fcontent = isset($_POST["fcontent"]) ? $_POST['fcontent'] : null;
        $fpublish = isset($_POST["fpublish"]) ? filter_var($_POST['fpublish'], FILTER_SANITIZE_STRING) : null;
        
        $update = array(
            'pg_title' => $ftitle,
            'pg_slug' => $fslug,
            'pg_content' => $fcontent,
            'pg_publish' => $fpublish
        );
        //Add the WHERE clauses
        $where_clause = array(
            'pg_id' => $fid
        );
        $updated = $database->update( 'site_pages', $update, $where_clause, 1 );
        if( $updated )
        {
            header('location:../../?page='.$getpage);
        }
    }

}
?>