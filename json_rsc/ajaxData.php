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

if ($getact == "get_tarif"){
    $data = array();
    $fharga = (int) htmlspecialchars($_GET["fharga"], ENT_QUOTES, 'UTF-8');
    $fberat = htmlspecialchars($_GET["fberat"], ENT_QUOTES, 'UTF-8');
    $fdim_p = (int) htmlspecialchars($_GET["fdim_p"], ENT_QUOTES, 'UTF-8');
    $fdim_l = (int) htmlspecialchars($_GET["fdim_l"], ENT_QUOTES, 'UTF-8');
    $fdim_t = (int) htmlspecialchars($_GET["fdim_t"], ENT_QUOTES, 'UTF-8');
    $fekspedisi = htmlspecialchars($_GET["fekspedisi"], ENT_QUOTES, 'UTF-8');
    $ftujuan = htmlspecialchars($_GET["ftujuan"], ENT_QUOTES, 'UTF-8');
    //Fetch all state data
    $query = "";
    if(empty($fdim_p) AND empty($fdim_l) AND empty($fdim_t)){
        $data = array();
    }else{
        if(empty($ftujuan)){
            $query = "SELECT bk.biaya_id, e.ekspedisi_nama, p.nama_provinsi, k.nama_kabupaten, bk.biaya_via_udara, "
                    . "bk.biaya_via_darat, bk.biaya_via_laut FROM biaya_kirim AS bk "
                    . "INNER JOIN kabupaten AS k ON bk.id_kabupaten = k.id_kabupaten "
                    . "INNER JOIN provinsi AS p ON k.id_provinsi = p.id_provinsi "
                    . "INNER JOIN ekspedisi AS e ON bk.ekspedisi_id = e.ekspedisi_id "
                    . "WHERE bk.ekspedisi_id = '".$fekspedisi."'";
        }else{
            $query = "SELECT bk.biaya_id, e.ekspedisi_nama, p.nama_provinsi, k.nama_kabupaten, bk.biaya_via_udara, "
                    . "bk.biaya_via_darat, bk.biaya_via_laut FROM biaya_kirim AS bk "
                    . "INNER JOIN kabupaten AS k ON bk.id_kabupaten = k.id_kabupaten "
                    . "INNER JOIN provinsi AS p ON k.id_provinsi = p.id_provinsi "
                    . "INNER JOIN ekspedisi AS e ON bk.ekspedisi_id = e.ekspedisi_id "
                    . "WHERE bk.ekspedisi_id = '".$fekspedisi."' "
                    . "AND bk.id_kabupaten = '".$ftujuan."'";
        }
        $results = $database->get_results( $query );
        foreach( $results as $r )
        {
            $budara = (int) $r["biaya_via_udara"];
            $bdarat = (int) $r["biaya_via_darat"];
            $blaut = (int) $r["biaya_via_laut"];
            $volume = ($fdim_p * $fdim_l * $fdim_t)/6000;
            $b_asuransi = round($fharga * 0.085);
            $btotal_udara = round(($budara * $volume) + $b_asuransi);
            $btotal_darat = round(($bdarat * $volume) + $b_asuransi);
            $btotal_laut = round(($blaut * $volume) + $b_asuransi);
            $btotal_udara_rp = format_IDR($btotal_udara);
            $btotal_darat_rp = format_IDR($btotal_darat);
            $btotal_laut_rp = format_IDR($btotal_laut);
                    
            $row["ekspedisi"] = nohtml($r["ekspedisi_nama"]);
            $row["tujuan"] = nohtml($r["nama_kabupaten"]);
            $row["tarif_asuransi"] = $b_asuransi;
            $row["via_udara"] = $btotal_udara;
            $row["via_darat"] = $btotal_darat;
            $row["via_laut"] = $btotal_laut;

            $data[] = $row;
        }
    }
    echo json_encode(array('data'=>$data));
}
?>