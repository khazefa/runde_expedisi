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
    if ($getpage == "data-barang" AND $getact == "save"){
        $fprincipal = isset($_POST["fprincipal"]) ? filter_var($_POST['fprincipal'], FILTER_SANITIZE_NUMBER_INT) : null;
        $fkode = isset($_POST["fkode"]) ? filter_var($_POST['fkode'], FILTER_SANITIZE_STRING) : null;
        $fnama = isset($_POST["fnama"]) ? filter_var($_POST['fnama'], FILTER_SANITIZE_STRING) : null;
        $fharga = isset($_POST["fhrg_brg"]) ? filter_var($_POST['fhrg_brg'], FILTER_SANITIZE_NUMBER_INT) : null;
        $fberat = isset($_POST["fbrt_brg"]) ? filter_var($_POST['fbrt_brg'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : null;
        $fdim_p = isset($_POST["fdim_p"]) ? filter_var($_POST['fdim_p'], FILTER_SANITIZE_NUMBER_INT) : null;
        $fdim_l = isset($_POST["fdim_l"]) ? filter_var($_POST['fdim_l'], FILTER_SANITIZE_NUMBER_INT) : null;
        $fdim_t = isset($_POST["fdim_t"]) ? filter_var($_POST['fdim_t'], FILTER_SANITIZE_NUMBER_INT) : null;
        
        $check_data = array(
            'brg_kode' => $fkode
        );
        $exists = $database->exists( 'barang', 'brg_kode', $check_data );
        
        if($exists){
            ?>
                <script>alert('Kode barang sudah ada, harap input kode barang yang lainnya.');</script>
            <?php
            header('location:../../?page='.$getpage);
        }else{
            $arrValue = array(
                'brg_kode' => $fkode,
                'principal_id' => $fprincipal,
                'brg_nama' => $fnama,
                'brg_hrg' => $fharga,
                'brg_berat' => $fberat,
                'brg_dim_p' => $fdim_p,
                'brg_dim_l' => $fdim_l,
                'brg_dim_t' => $fdim_t
            );
            $add_query = $database->insert( 'barang', $arrValue );
            if( $add_query )
            {
                header('location:../../?page='.$getpage);
            }
        }
    }
    // Update data
    elseif ($getpage == "data-barang" AND $getact == "update"){
        $fid = isset($_POST["fid"]) ? filter_var($_POST['fid'], FILTER_SANITIZE_NUMBER_INT) : 0;
        $fprincipal = isset($_POST["fprincipal"]) ? filter_var($_POST['fprincipal'], FILTER_SANITIZE_NUMBER_INT) : null;
        $fkode = isset($_POST["fkode"]) ? filter_var($_POST['fkode'], FILTER_SANITIZE_STRING) : null;
        $fnama = isset($_POST["fnama"]) ? filter_var($_POST['fnama'], FILTER_SANITIZE_STRING) : null;
        $fharga = isset($_POST["fhrg_brg"]) ? filter_var($_POST['fhrg_brg'], FILTER_SANITIZE_NUMBER_INT) : null;
        $fberat = isset($_POST["fbrt_brg"]) ? filter_var($_POST['fbrt_brg'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : null;
        $fdim_p = isset($_POST["fdim_p"]) ? filter_var($_POST['fdim_p'], FILTER_SANITIZE_NUMBER_INT) : null;
        $fdim_l = isset($_POST["fdim_l"]) ? filter_var($_POST['fdim_l'], FILTER_SANITIZE_NUMBER_INT) : null;
        $fdim_t = isset($_POST["fdim_t"]) ? filter_var($_POST['fdim_t'], FILTER_SANITIZE_NUMBER_INT) : null;
        
        $update = array(
            'principal_id' => $fprincipal,
            'brg_nama' => $fnama,
            'brg_hrg' => $fharga,
            'brg_berat' => $fberat,
            'brg_dim_p' => $fdim_p,
            'brg_dim_l' => $fdim_l,
            'brg_dim_t' => $fdim_t
        );
        //Add the WHERE clauses
        $where_clause = array(
            'brg_id' => $fid
        );
        $updated = $database->update( 'barang', $update, $where_clause, 1 );
        if( $updated )
        {
            header('location:../../?page='.$getpage);
        }
    }
    // Delete data
    elseif ($getpage == "data-barang" AND $getact == "delete"){
        $key = htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8');
        $where_clause = array(
            'brg_kode' => $key
        );
        //Query delete
        $deleted = $database->delete( 'barang', $where_clause);
        if( $deleted )
        {
            header('location:../../?page='.$getpage);
        }
    }
}
?>