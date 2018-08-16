<?php
error_reporting(0);
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    $pagetitle = "Sales Report";
    $act = "modules/reports/print_orders.php";

    $getpage = "sales-report";
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
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="pull-left"> Select date range to print out report</span>
            </div>
            <div class="panel-body">
                <form role="form" class="form-horizontal" method="POST" action="<?php echo $act;?>" target="_blank">
                    <input type="hidden" name="furl" value="<?php echo $getpage;?>" readonly="readonly">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Sales Date</label>
                        <div class="col-sm-4">
                            <input type="date" name="fdatepre" id="fdatepre" class="form-control" required="required">
                        </div>
                        <label class="col-sm-1 control-label">to </label>
                        <div class="col-sm-4">
                            <input type="date" name="fdatepost" id="fdatepost" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Print</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!--/.row-->
<?php

break;
}
}
?>