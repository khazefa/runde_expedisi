<?php
require("../includes/constants.php");
require('../includes/class.db.php');
$database = DB::getInstance();
require("../includes/global_helper.php");
require("../includes/common_helper.php");

$getact = htmlspecialchars($_GET["act"], ENT_QUOTES, 'UTF-8');

if ($getact == "get_kabupaten"){
    $key = htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8');
    //Fetch all state data
    $query = "SELECT * FROM kabupaten WHERE id_provinsi = ".$key."";
    $results = $database->get_results( $query );
    foreach( $results as $row )
    {
        echo "<option value='$row[id_kabupaten]'>$row[nama_kabupaten]</option>";
    }
}

if ($getact == "get_barang"){
    $key = htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8');
    //Fetch all state data
    $query = "";
    if($key === "" OR empty($key)){
        $query = "SELECT * FROM barang";
    }else{
        $query = "SELECT * FROM barang WHERE brg_nama LIKE '%".$key."%'";
    }
    $results = $database->get_results( $query );
    foreach( $results as $row )
    {
        echo "<option value='$row[id_kabupaten]'>$row[nama_kabupaten]</option>";
    }
}
?>