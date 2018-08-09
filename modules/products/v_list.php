<?php
error_reporting(0);
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    $pagetitle = "All Items";
    $act = "modules/products/do_task.php";

    $getpage = "items";
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
                <span class="pull-right"><button class="btn btn-primary" onclick="location.href='?page=<?php echo $getpage; ?>&act=add';"><i class="fa fa-plus-circle"></i> Add New</button> </span>
            </div>
            <div class="panel-body">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Item Name</th>
                            <th>Category Name</th>
                            <th>Brand Name</th>
                            <th>Item Price</th>
                            <th>Item Stock</th>
                            <th>Item Pict</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT p.*, c.category_name, b.brand_name FROM products AS p "
                                    . "INNER JOIN products_category AS c ON p.category_id = c.category_id "
                                    . "INNER JOIN products_brand AS b ON p.brand_id = b.brand_id ";
                            $results = $database->get_results( $query );
                            $no = 1;
                            foreach( $results as $row )
                            {
                                $img_path = "../" . UPLOADS_DIR . "products" . DIRECTORY_SEPARATOR;
                                $pict = !empty($row[product_pict]) ? "<img class='img-responsive' src='$img_path$row[product_pict]' width='100px'>" : "NO IMAGE";
                                echo "<tr>";
                                    echo "<td>
                                            <a href='?page=$getpage&act=edit&key=$row[product_uniqid]'><i class='fa fa-edit'></i> Edit</a> | 
                                            <a href='$act?page=$getpage&act=delete&key=$row[product_uniqid]'><i class='fa fa-trash'></i> Delete</a>
                                        </td>";
                                    echo "<td>$row[product_name]</td>";
                                    echo "<td>$row[category_name]</td>";
                                    echo "<td>$row[brand_name]</td>";
                                    echo "<td>Rp. ". format_IDR($row[product_price])."</td>";
                                    echo "<td>$row[product_stock]</td>";
                                    echo "<td class='text-center'>$pict</td>";
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

case "add":
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Data Form</h5>
            </div>
            <div class="panel-body">
                <form role="form" class="form-horizontal" method="POST" action="<?php echo $act.'?page='.$getpage;?>&act=save" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category Name</label>
                        <div class="col-sm-6">
                            <select name="fcategory" class="form-control" id="fcategory">
                                <?php
                                    $query = "SELECT * FROM products_category ORDER BY category_name";
                                    $results = $database->get_results( $query );
                                    foreach( $results as $row )
                                    {
                                        echo "<option value='$row[category_id]'>$row[category_name]</option>";
                                    }
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Brand Name</label>
                        <div class="col-sm-6">
                            <select name="fbrand" class="form-control" id="fbrand">
                                <?php
                                    $query = "SELECT * FROM products_brand ORDER BY brand_name";
                                    $results = $database->get_results( $query );
                                    foreach( $results as $row )
                                    {
                                        echo "<option value='$row[brand_id]'>$row[brand_name]</option>";
                                    }
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Name</label>
                        <div class="col-sm-6">
                            <input type="text" name="fname" class="form-control" id="fname" placeholder="Item Name" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Description</label>
                        <div class="col-sm-8">
                            <textarea name="fdesc" class="form-control summernote" id="fdesc"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Price</label>
                        <div class="col-sm-6">
                            <input type="text" name="fprice" class="form-control" id="fprice" placeholder="Item Price" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Discount</label>
                        <div class="col-sm-6">
                            <input type="number" name="fdisc" class="form-control" id="fdisc" placeholder="Item Discount">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Stock</label>
                        <div class="col-sm-6">
                            <input type="number" name="fstock" class="form-control" id="fstock" placeholder="Item Stock">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Weight (Kg)</label>
                        <div class="col-sm-6">
                            <input type="number" name="fweight" class="form-control" id="fweight" placeholder="Item Weight">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Picture</label>
                        <div class="col-sm-6">
                            <input type="file" name="fupload" class="form-control" id="fupload" accept="image/x-png,image/jpeg">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!--/.row-->
<?php
break;

case "edit";
$key = htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8');
$query = "SELECT * FROM products WHERE product_uniqid = '$key' ";
if( $database->num_rows( $query ) > 0 )
{
    list( $id, $key, $category, $brand, $name, $desc, $price, $stock, $weight, $disc, $pict ) = $database->get_row( $query );
    
    $img_path = "../" . UPLOADS_DIR . "products" . DIRECTORY_SEPARATOR;
    $img = !empty($pict) ? "<img class='img-responsive' src='$img_path$pict'>" : "NO IMAGE";
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Data Form</h5>
            </div>
            <div class="panel-body">
                <form role="form" class="form-horizontal" method="POST" action="<?php echo $act.'?page='.$getpage;?>&act=update" enctype="multipart/form-data">
                <input type="hidden" name="fkey" value="<?php echo $key;?>" readonly>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category Name</label>
                        <div class="col-sm-6">
                            <select name="fcategory" class="form-control" id="fcategory">
                                <?php
                                    $query = "SELECT * FROM products_category ORDER BY category_name";
                                    $results = $database->get_results( $query );
                                    foreach( $results as $row )
                                    {
                                        if($row[category_id] == $category){
                                            echo "<option value='$row[category_id]' selected>$row[category_name]</option>";
                                        }else{
                                            echo "<option value='$row[category_id]'>$row[category_name]</option>";
                                        }
                                        
                                    }
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Brand Name</label>
                        <div class="col-sm-6">
                            <select name="fbrand" class="form-control" id="fbrand">
                                <?php
                                    $query = "SELECT * FROM products_brand ORDER BY brand_name";
                                    $results = $database->get_results( $query );
                                    foreach( $results as $row )
                                    {
                                        if($row[brand_id] == $brand){
                                            echo "<option value='$row[brand_id]' selected>$row[brand_name]</option>";
                                        }else{
                                            echo "<option value='$row[brand_id]'>$row[brand_name]</option>";
                                        }
                                        
                                    }
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Name</label>
                        <div class="col-sm-6">
                            <input type="text" name="fname" class="form-control" id="fname" value="<?php echo $name;?>" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Description</label>
                        <div class="col-sm-8">
                            <textarea name="fdesc" class="form-control summernote" id="fdesc"><?php echo $desc;?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Price</label>
                        <div class="col-sm-6">
                            <input type="text" name="fprice" class="form-control" id="fprice" value="<?php echo $price;?>" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Discount</label>
                        <div class="col-sm-6">
                            <input type="number" name="fdisc" class="form-control" id="fdisc" value="<?php echo $disc;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Stock</label>
                        <div class="col-sm-6">
                            <input type="number" name="fstock" class="form-control" id="fstock" value="<?php echo $stock;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Weight (Kg)</label>
                        <div class="col-sm-6">
                            <input type="number" name="fweight" class="form-control" id="fweight" value="<?php echo $weight;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Picture</label>
                        <div class="col-sm-6">
                            <?php echo $img;?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Change Picture</label>
                        <div class="col-sm-6">
                            <input type="file" name="fupload" class="form-control" id="fupload" accept="image/x-png,image/jpeg">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
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