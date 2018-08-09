<?php
error_reporting(0);
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    $pagetitle = "Customer Orders";
    $act = "modules/orders/do_task.php";

    $getpage = "customer-orders";
    $getact = htmlspecialchars($_GET["act"], ENT_QUOTES, 'UTF-8');
?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl;?>">
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
<?php
switch($getact){
    // Show List
    default:
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="pull-right"> &nbsp;</span>
            </div>
            <div class="panel-body">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT o.order_id, o.order_uniqid, o.order_date, o.order_subtotal, o.order_status, c.customer_name "
                                    . "FROM orders AS o INNER JOIN customers AS c ON o.customer_uniqid = c.customer_uniqid ORDER BY order_id DESC";
                            $results = $database->get_results( $query );
                            $no = 1;
                            foreach( $results as $row )
                            {
                                echo "<tr>";
                                    echo "<td>
                                            <a href='?page=$getpage&act=info&key=$row[order_uniqid]'><i class='fa fa-eye'></i> View</a>
                                        </td>";
                                    echo "<td>#$row[order_uniqid]</td>";
                                    echo "<td>$row[customer_name]</td>";
                                    echo "<td>". tgl_indo($row[order_date]) ."</td>";
                                    echo "<td>RP. ". format_IDR($row[order_subtotal]) ."</td>";
                                    echo "<td>". strtoupper($row[order_status]) ."</td>";
                                echo "</tr>";
                                $no++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!--/.row-->
<?php
break;

case "info";
$key = htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8');
$query = "SELECT o.order_id, o.order_uniqid, o.customer_uniqid, o.order_qty, o.order_subtotal, o.destination, s.shipping_dest, s.shipping_cost, o.order_status "
        . "FROM orders AS o "
        . "INNER JOIN shipping AS s ON o.shipping_id = s.shipping_id "
        . "WHERE o.order_uniqid = '$key' ";
if( $database->num_rows( $query ) > 0 )
{
    list( $id, $uniqid, $customer, $order_qty, $order_subtotal, $dest, $sdest, $scost, $status ) = $database->get_row( $query );
    $shippcost = $order_qty * $scost;
    $total = $order_subtotal + $scost;
    $total_qty = 0;
    
    if ($status=='invoiced'){
//        $sp = array(array('paid','Paid'),array('sent','Sent'),array('cancel','Cancel'));
        $sp = array(array('cancel','Cancel'));
    }
    elseif ($status=='paid'){
        $sp = array(array('sent','Sent'),array('cancel','Cancel'));
    }
    elseif ($status=='sent'){
        $sp = array(array('complete','Complete'));
    }
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Invoice #<?php echo $uniqid;?></h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Produk</th>
                            <th></th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $qryp = "SELECT p.product_pict, p.product_name, od.qty, p.product_price, "
                                    . "od.discount, od.subtotal FROM orders_detail AS od "
                                    . "INNER JOIN products AS p "
                                    . "ON od.product_uniqid = p.product_uniqid "
                                    . "WHERE od.order_uniqid = '$key' ";
                            $results = $database->get_results( $qryp );
                            $no = 1;
                            foreach( $results as $row )
                            {
                                $img_path = "../" . UPLOADS_DIR . "products" . DIRECTORY_SEPARATOR;
                                $pict = !empty($row[product_pict]) ? "<img class='img-responsive' src='$img_path$row[product_pict]' width='100px'>" : "NO IMAGE";

                                $fdiscount = format_IDR($row["discount"]);
                                $total_qty = $total_qty + $row["qty"];
                                echo "<tr>";
                                    echo "<td class='text-center'>$pict</td>";
                                    echo "<td>$row[product_name]</td>";
                                    echo "<td>$row[qty]</td>";
                                    echo "<td>RP. ". format_IDR($row[product_price]) ."</td>";
                                    echo "<td>RP. ". $fdiscount ."</td>";
                                    echo "<td>RP. ". format_IDR($row[subtotal]) ."</td>";
                                echo "</tr>";
                            }
                            $fshipping_cost = $total_qty * $shippcost;
                            $ftotal = $total + $fshipping_cost;
                        ?>
                        </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <!--<label>Tujuan Pengiriman</label>-->
                        <table class="table">
                            <?php
                                $qryc = "SELECT customer_name, customer_email, customer_phone "
                                        . "FROM customers WHERE customer_uniqid = '$customer' ";
                                if( $database->num_rows( $qryc ) > 0 )
                                {
                                    list( $cname, $cmail, $cphone, $caddr, $ccity, $cpostcode ) = $database->get_row( $qryc );
                                }
                            ?>
                            <tr>
                                <td>Nama Penerima</td><td>: <?php echo $cname;?></td>
                            </tr>
                            <tr>
                                <td>Alamat Email</td><td>: <?php echo $cmail;?></td>
                            </tr>
                            <tr>
                                <td>No. Telepon</td><td>: <?php echo $cphone;?></td>
                            </tr>
                            <tr>
                                <td>Alamat Tujuan</td><td>: <?php echo $dest;?></td>
                            </tr>
                            <tr>
                                <td>Kota Tujuan</td><td>: <?php echo $sdest;?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table class="table">
                            <tr>
                                <td>Subtotal Order</td><td>: <?php echo "Rp. ".format_IDR($order_subtotal);?></td>
                            </tr>
                            <tr>
                                <td>Biaya Kirim</td><td>: <?php echo "Rp. ".format_IDR($fshipping_cost);?></td>
                            </tr>
                            <tr>
                                <td>Total</td><td>: <?php echo "Rp. ".format_IDR($ftotal);?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <form role="form" class="form-horizontal" method="POST" action="<?php echo $act.'?page='.$getpage;?>&act=update">
                <input type="hidden" name="fid" value="<?php echo $id;?>" readonly>
                <input type="hidden" name="fkey" value="<?php echo $key;?>" readonly>
                    <div class="form-group">
                        <?php
                            if($status == "complete"){
                                echo '<label class="col-sm-2 control-label text-success">Order Completed</label>';
                            }elseif($status == "cancel"){
                                echo '<label class="col-sm-2 control-label text-danger">Order Canceled</label>';
                            }else{
                        ?>
                        <label class="col-sm-2 control-label">Change Status</label>
                        <div class="col-sm-2">
                            <select name="fstatus" class="form-control" id="fstatus">
                                <?php
                                foreach ($sp as $key => $arr) {
                                    echo '<option value="'.$arr[0].'">'.$arr[1].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!--/.row-->
<?php
}else{
echo '<div class="row"><div class="col-lg-12"> '
    . '<div class="panel panel-default">'
    . '<div class="panel-body"><h2 class="text-center">Data Not Available</h2></div>'
    . '</div>'
    . '</div></div>';
}
break;
}
}
?>