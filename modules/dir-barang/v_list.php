<?php
error_reporting(0);
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    $pagetitle = "Barang";
    $act = "modules/dir-barang/do_task.php";

    $getpage = "data-barang";
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
                            <th>Aksi</th>
                            <th>Principal</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Harga (Rp)</th>
                            <th>Berat (Kg)</th>
                            <th>Dimensi (P x L x T)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT p.principal_nama, b.brg_kode, b.brg_nama, b.brg_hrg, b.brg_berat, "
                                    . "b.brg_dim_p, b.brg_dim_l, b.brg_dim_t FROM barang AS b "
                                    . "INNER JOIN principal AS p ON b.principal_id = p.principal_id";
                            $results = $database->get_results( $query );
                            $no = 1;
                            foreach( $results as $row )
                            {
                                $harga = format_IDR($row["brg_hrg"]);
                                echo "<tr>";
                                    echo "<td>
                                            <a href='?page=$getpage&act=edit&key=$row[brg_kode]'><i class='fa fa-edit'></i> Edit</a> | 
                                            <a href='$act?page=$getpage&act=delete&key=$row[brg_kode]'><i class='fa fa-trash'></i> Delete</a>
                                        </td>";
                                    echo "<td>$row[principal_nama]</td>";
                                    echo "<td>$row[brg_kode]</td>";
                                    echo "<td>$row[brg_nama]</td>";
                                    echo "<td>Rp. $harga</td>";
                                    echo "<td>$row[brg_berat]</td>";
                                    echo "<td>$row[brg_dim_p] x $row[brg_dim_l] x $row[brg_dim_t]</td>";
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
                <form role="form" class="form-horizontal" method="POST" action="<?php echo $act.'?page='.$getpage;?>&act=save">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Principal</label>
                            <div class="col-sm-6">
                                <select name="fprincipal" class="form-control" id="fprincipal">
                                    <?php
                                        $query = "SELECT * FROM principal ORDER BY principal_nama";
                                        $results = $database->get_results( $query );
                                        foreach( $results as $row )
                                        {
                                            echo "<option value='$row[principal_id]'>$row[principal_nama]</option>";
                                        }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Kode Barang</label>
                            <div class="col-sm-4">
                                <input type="text" name="fkode" class="form-control" id="fkode" placeholder="Kode Barang" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nama Barang</label>
                            <div class="col-sm-7">
                                <input type="text" name="fnama" class="form-control" id="fnama" placeholder="Nama Barang" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Harga Barang (Rp)</label>
                            <div class="col-sm-4">
                                <input type="number" min="0" name="fhrg_brg" class="form-control" id="fhrg_brg" value="10000" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Berat (Kg)</label>
                            <div class="col-sm-4">
                                <input type="number" min="0" name="fbrt_brg" class="form-control" id="fbrt_brg" 
                                       pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" value="10" required="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Dimensi Panjang</label>
                            <div class="col-sm-3">
                                <input type="number" min="0" name="fdim_p" class="form-control" id="fdim_p" value="10" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Dimensi Lebar</label>
                            <div class="col-sm-3">
                                <input type="number" min="0" name="fdim_l" class="form-control" id="fdim_l" value="10" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Dimensi Tinggi</label>
                            <div class="col-sm-3">
                                <input type="number" min="0" name="fdim_t" class="form-control" id="fdim_t" value="10" required="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <hr>
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-5">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
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
$query = "SELECT * FROM barang WHERE brg_kode = '$key' ";
if( $database->num_rows( $query ) > 0 )
{
    list( $id, $kode, $principal, $nama, $hrg, $brt, $dim_p, $dim_l, $dim_t ) = $database->get_row( $query );
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Data Form</h5>
            </div>
            <div class="panel-body">
                <form role="form" class="form-horizontal" method="POST" action="<?php echo $act.'?page='.$getpage;?>&act=update">
                <input type="hidden" name="fid" value="<?php echo $id;?>" readonly>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Principal</label>
                            <div class="col-sm-6">
                                <select name="fprincipal" class="form-control" id="fprincipal">
                                    <?php
                                        $query = "SELECT * FROM principal ORDER BY principal_nama";
                                        $results = $database->get_results( $query );
                                        foreach( $results as $row )
                                        {
                                            if($row['principal_id'] == $principal){
                                                echo "<option value='$row[principal_id]' selected>$row[principal_nama]</option>";
                                            }else{
                                                echo "<option value='$row[principal_id]'>$row[principal_nama]</option>";
                                            }
                                        }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Kode Barang</label>
                            <div class="col-sm-4">
                                <input type="text" name="fkode" class="form-control" id="fkode" value="<?php echo $kode; ?>" readonly="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nama Barang</label>
                            <div class="col-sm-7">
                                <input type="text" name="fnama" class="form-control" id="fnama" value="<?php echo $nama; ?>" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Harga Barang (Rp)</label>
                            <div class="col-sm-4">
                                <input type="number" min="0" name="fhrg_brg" class="form-control" id="fhrg_brg" value="<?php echo $hrg; ?>" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Berat (Kg)</label>
                            <div class="col-sm-4">
                                <input type="number" min="0" name="fbrt_brg" class="form-control" id="fbrt_brg" 
                                       pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" value="<?php echo $brt; ?>" required="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Dimensi Panjang</label>
                            <div class="col-sm-3">
                                <input type="number" min="0" name="fdim_p" class="form-control" id="fdim_p" value="<?php echo $dim_p; ?>" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Dimensi Lebar</label>
                            <div class="col-sm-3">
                                <input type="number" min="0" name="fdim_l" class="form-control" id="fdim_l" value="<?php echo $dim_l; ?>" required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Dimensi Tinggi</label>
                            <div class="col-sm-3">
                                <input type="number" min="0" name="fdim_t" class="form-control" id="fdim_t" value="<?php echo $dim_t; ?>" required="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <hr>
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-5">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
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