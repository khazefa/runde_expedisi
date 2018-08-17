<?php
error_reporting(0);
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    $pagetitle = "Dashboard";

//    $query_o = "SELECT order_id FROM orders WHERE DATE(order_date) = CURDATE() AND order_status = 'invoiced'";
//    $num_o = (int)$database->num_rows( $query_o );
//
//    $query_py = "SELECT payment_id FROM payment WHERE DATE(created_date) = CURDATE() AND payment_status = 'pending'";
//    $num_py = (int)$database->num_rows( $query_py );
//
    $query_m = "SELECT brg_id FROM barang";
    $num_m = (int)$database->num_rows( $query_m );
//
    $query_p = "SELECT ekspedisi_id FROM ekspedisi";
    $num_p = (int)$database->num_rows( $query_p );

?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
            <em class="fa fa-home"></em>
        </a></li>
        <li class="active"><?php echo $pagetitle;?></li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?php echo $pagetitle;?></h1>
    </div>
</div><!--/.row-->

<div class="panel panel-container">
                <div class="row">
                        <div class="col-xs-6 col-md-6 col-lg-6 no-padding">
                                <div class="panel panel-orange panel-widget border-right">
                                        <div class="row no-padding"><em class="fa fa-xl fa-tags color-teal"></em>
                                            <div class="large">
                                                <?php echo $num_m;?>
                                            </div>
                                            <div class="text-muted">Total Barang</div>
                                        </div>
                                </div>
                        </div>
                        <div class="col-xs-6 col-md-6 col-lg-6 no-padding">
                                <div class="panel panel-red panel-widget ">
                                        <div class="row no-padding"><em class="fa fa-xl fa-truck color-red"></em>
                                            <div class="large">
                                                <?php echo $num_p;?>
                                            </div>
                                            <div class="text-muted">Total Ekspedisi</div>
                                        </div>
                                </div>
                        </div>
                </div><!--/.row-->
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo WEB_TITLE;?></div>
            <div class="panel-body">
                <p>Selamat datang di halaman admin, harap pergunakan 
                    fitur-fitur pada menu-menu yang ada di sebelah kiri untuk mengelola aplikasi.</p>
            </div>
        </div>
    </div>
</div><!--/.row-->
<?php
}
?>