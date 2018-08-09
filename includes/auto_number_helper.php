<?php
function member_id($param='M') {
    $dataMax = mysqli_fetch_assoc(mysqli_query("SELECT SUBSTR(MAX(id_member),-4) AS Mid FROM member"));
    if($dataMax['Mid']=='') { // bila data kosong
        $Daftar = $param."0001";
    }else {
        $MaksDaftar = $dataMax['Mid'];
        $MaksDaftar++;
        if($MaksDaftar < 10){ $Daftar = $param."000".$MaksDaftar;} // nilai kurang dari 10
        else if($MaksDaftar < 100){ $Daftar = $param."00".$MaksDaftar;} // nilai kurang dari 100
        else if($MaksDaftar < 1000){ $Daftar = $param."0".$MaksDaftar;} // nilai kurang dari 1000
        else {$Daftar = $MaksDaftar;} // lebih dari 1000
    }
    return $Daftar;
}
		
function order_id($param='F') {
    $dataMax = mysqli_fetch_assoc(mysqli_query("SELECT SUBSTR(MAX(order_id),-4) AS key FROM orders"));
    if($dataMax['key']=='') { // bila data kosong
        $Order = $param.date("m").date("d")."0001";
    }else {
        $MaksOrder = $dataMax['Oid'];
        $MaksOrder++;
        if($MaksOrder < 10){ $Order = $param.date("m").date("d")."000".$MaksOrder;} // nilai kurang dari 10
        else if($MaksOrder < 100){ $Order = $param.date("m").date("d")."00".$MaksOrder;} // nilai kurang dari 100
        else if($MaksOrder < 1000){ $Order = $param.date("m").date("d")."0".$MaksOrder;} // nilai kurang dari 1000
        else {$Order = $MaksOrder;} // lebih dari 1000
    }
    return $Order;
}
?>