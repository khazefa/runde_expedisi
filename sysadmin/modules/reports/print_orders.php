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
    require('../../../includes/fpdf_cellfit.php');
    $database = DB::getInstance();
    require_once("../../../includes/common_helper.php");

    $furl = isset($_POST["furl"]) ? filter_var($_POST['furl'], FILTER_SANITIZE_STRING) : null;
    
    $fdatepre = isset($_POST["fdatepre"]) ? filter_var($_POST['fdatepre'], FILTER_SANITIZE_STRING) : null;
    $fdatepost = isset($_POST["fdatepost"]) ? filter_var($_POST['fdatepost'], FILTER_SANITIZE_STRING) : null;
    
    $datepre = tgl_indo($fdatepre);
    $datepost = tgl_indo($fdatepost);
    
    $orientation = "P";
    $paper_size = "A4";
    $width = 0;
    $height = 0;

    switch ($orientation) {
        case "P":
           switch ($paper_size) {
                       case "A4":
                            $width = 210;
                            $height = 297;
                       break;
                       case "A5":
                            $width = 148;
                            $height = 210;
                       break;
                       default:
                            $width = 210;
                            $height = 297;
                       break;
               }
            break;

        case "L":
            switch ($paper_size) {
                       case "A4":
                            $width = 297;
                            $height = 210;
                       break;
                       case "A5":
                            $width = 210;
                            $height = 148;
                       break;
                       default:
                            $width = 297;
                            $height = 210;
                       break;
               }
            break;

        default:
            switch ($paper_size) {
                       case "A4":
                            $width = 210;
                            $height = 297;
                       break;
                       case "A5":
                            $width = 148;
                            $height = 210;
                       break;
                       default:
                            $width = 210;
                            $height = 297;
                       break;
               }
            break;
    }

    // intance object dan memberikan pengaturan halaman PDF
    $pdf = new FPDF_CellFit($orientation,'mm',$paper_size);

    $pdf->AliasNbPages();
    // membuat halaman baru
    $pdf->AddPage();
    // setting jenis font yang akan digunakan
    $pdf->Image('../../../assets/img/logo.png',10,8,42,15);
    $pdf->SetFont('Arial','B',16);
    // mencetak string 
    $pdf->Cell(190,7,'SALES REPORT',0,1,'C');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(190,7,'Ardhanshop',0,1,'C');

    // Garis atas untuk header
    $pdf->Line(10, 30, 210-10, 30);
    // Memberikan space kebawah agar tidak terlalu rapat
    $pdf->Cell(10,10,'',0,1);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(($width*(5/100)),6,'NO',1,0);
    $pdf->Cell(($width*(10/100)),6,'ORDER ID',1,0);
    $pdf->Cell(($width*(15/100)),6,'ORDER DATE',1,0);
    $pdf->Cell(($width*(25/100)),6,'CUSTOMER',1,0);
    $pdf->Cell(($width*(5/100)),6,'QTY',1,0);
    $pdf->Cell(($width*(20/100)),6,'ORDER TOTAL',1,1);

    $pdf->SetFont('Arial','',9);
    
    $query = "SELECT o.order_uniqid, o.order_date, c.customer_name, o.order_qty, "
            . "o.order_subtotal, o.order_status FROM orders AS o "
            . "INNER JOIN customers AS c ON o.customer_uniqid = c.customer_uniqid "
            . "WHERE o.order_status = 'complete' AND (o.order_date BETWEEN '$fdatepre' AND '$fdatepost') "
            . "ORDER BY o.order_date ASC";
    $no = 1;
    $total_qty = 0;
    $total_sales = 0;
    $total_sales_rp = "";
//    if( $database->num_rows( $query ) > 0 )
//    {
        $results = $database->get_results( $query );
        foreach( $results as $row )
        {
            $date = tgl_indo($row['order_date']);
            $qty = (int)$row['order_qty'];
            $subtotal = $row['order_subtotal'];
            $subtotal_rp = "Rp. ".format_IDR($row['order_subtotal']);
            $status = strtoupper($row['order_status']);
            $total_qty = $total_qty + $qty;
            $total_sales = $total_sales + $subtotal;
            $total_sales_rp = "Rp. ".format_IDR($total_sales);

            $pdf->Cell(($width*(5/100)),6,$no,1,0);
            $pdf->CellFitScale(($width*(10/100)),6,$row['order_uniqid'],1,0);
            $pdf->CellFitScale(($width*(15/100)),6,$date,1,0);
            $pdf->CellFitScale(($width*(25/100)),6,$row['customer_name'],1,0);
            $pdf->CellFitScale(($width*(5/100)),6,$qty,1,0);
            $pdf->CellFitScale(($width*(20/100)),6,$subtotal_rp,1,1);
            $no++;
        }
//    }else{
//        header('location:../../?page='.$furl);
//    }
    
    $pdf->Ln(1);
    $pdf->Cell(($width*(90/100)),7,'Total Qty : '.$total_qty.' items',0,1,'R');
    $pdf->Cell(($width*(90/100)),7,'Total Sales : '.$total_sales_rp,0,1,'R');

    $title = 'Sales Report ('.$datepre.' to '.$datepost.')';
    $pdf->SetTitle($title);
    $pdf->Output('I', $title.'.pdf');
}
?>