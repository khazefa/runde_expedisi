<?php
session_start();
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    require("../../../includes/constants.php");
    require("../../../includes/common_helper.php");
    require_once("../../../includes/class.db.php");
//    include("../../../includes/verot_upload/class.upload.php");
    $database = DB::getInstance();

    $getpage = htmlspecialchars($_GET["page"], ENT_QUOTES, 'UTF-8');
    $getact = htmlspecialchars($_GET["act"], ENT_QUOTES, 'UTF-8');

    // Save data
    if ($getpage == "items" AND $getact == "save"){
        $random = rand(000000,999999);
        $funiqid = strtoupper(generateRandomString());
        $fcategory = isset($_POST["fcategory"]) ? filter_var($_POST['fcategory'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fbrand = isset($_POST["fbrand"]) ? filter_var($_POST['fbrand'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fname = isset($_POST["fname"]) ? filter_var($_POST['fname'], FILTER_SANITIZE_STRING) : null;
//        $fdesc = isset($_POST["fdesc"]) ? filter_var($_POST['fdesc'], FILTER_SANITIZE_STRING) : null;
        $fdesc = isset($_POST["fdesc"]) ? $_POST['fdesc'] : null;
        $fprice = isset($_POST["fprice"]) ? filter_var($_POST['fprice'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fdisc = isset($_POST["fdisc"]) ? filter_var($_POST['fdisc'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fstock = isset($_POST["fstock"]) ? filter_var($_POST['fstock'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fweight = isset($_POST["fweight"]) ? filter_var($_POST['fweight'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fupload = $_FILES['fupload'];
        
        $arrValue = array();
        
        if(empty($fupload['tmp_name'])){
            $arrValue = array(
                'product_uniqid' => $funiqid,
                'category_id' => $fcategory,
                'brand_id' => $fbrand,
                'product_name' => $fname,
                'product_desc' => $fdesc,
                'product_price' => $fprice,
                'product_stock' => $fstock,
                'product_weight' => $fweight,
                'product_disc' => $fdisc
            );
        }else{
            uploadFile($fupload, $random, "products");
            $arrValue = array(
                'product_uniqid' => $funiqid,
                'category_id' => $fcategory,
                'brand_id' => $fbrand,
                'product_name' => $fname,
                'product_desc' => $fdesc,
                'product_price' => $fprice,
                'product_stock' => $fstock,
                'product_weight' => $fweight,
                'product_disc' => $fdisc,
                'product_pict' => $random.$fupload['name']
            );
        }

        $add_query = $database->insert( 'products', $arrValue );
        if( $add_query )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Update data
    elseif ($getpage == "items" AND $getact == "update"){
        $random = rand(000000,999999);
        $fkey = isset($_POST["fkey"]) ? filter_var($_POST['fkey'], FILTER_SANITIZE_STRING) : null;
        $fcategory = isset($_POST["fcategory"]) ? filter_var($_POST['fcategory'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fbrand = isset($_POST["fbrand"]) ? filter_var($_POST['fbrand'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fname = isset($_POST["fname"]) ? filter_var($_POST['fname'], FILTER_SANITIZE_STRING) : null;
//        $fdesc = isset($_POST["fdesc"]) ? filter_var($_POST['fdesc'], FILTER_SANITIZE_STRING) : null;
        $fdesc = isset($_POST["fdesc"]) ? $_POST['fdesc'] : null;
        $fprice = isset($_POST["fprice"]) ? filter_var($_POST['fprice'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fdisc = isset($_POST["fdisc"]) ? filter_var($_POST['fdisc'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fstock = isset($_POST["fstock"]) ? filter_var($_POST['fstock'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fweight = isset($_POST["fweight"]) ? filter_var($_POST['fweight'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fupload = $_FILES['fupload'];
        
        $arrValue = array();
        
        if(empty($fupload['tmp_name'])){
            $arrValue = array(
                'category_id' => $fcategory,
                'brand_id' => $fbrand,
                'product_name' => $fname,
                'product_desc' => $fdesc,
                'product_price' => $fprice,
                'product_stock' => $fstock,
                'product_weight' => $fweight,
                'product_disc' => $fdisc
            );
        }else{
            uploadFile($fupload, $random, "products");
            $arrValue = array(
                'category_id' => $fcategory,
                'brand_id' => $fbrand,
                'product_name' => $fname,
                'product_desc' => $fdesc,
                'product_price' => $fprice,
                'product_stock' => $fstock,
                'product_weight' => $fweight,
                'product_disc' => $fdisc,
                'product_pict' => $random.$fupload['name']
            );
        }
        
        //Add the WHERE clauses
        $arrWhere = array(
            'product_uniqid' => $fkey
        );
        $updated = $database->update( 'products', $arrValue, $arrWhere, 1 );
        if( $updated )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Delete data
    elseif ($getpage == "items" AND $getact == "delete"){
        $key = htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8');
        $query = "SELECT product_pict FROM products WHERE product_uniqid = '$key' ";
        //Add the WHERE clauses
        $where_clause = array(
            'product_uniqid' => $key
        );
        if( $database->num_rows( $query ) > 0 )
        {
            list( $pict ) = $database->get_row( $query );
        }
        if (!empty($pict)){
            //Query delete
            $deleted = $database->delete( 'products', $where_clause);
            if( $deleted )
            {
                unlink("../../../uploads/products/$pict");
            }
             
        }
        else{
            //Query delete
            $deleted = $database->delete( 'products', $where_clause);
        }
        header('location:../../?page='.$getpage);
    }
}
?>