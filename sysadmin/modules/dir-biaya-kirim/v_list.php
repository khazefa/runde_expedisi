<?php
error_reporting(0);
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    $pagetitle = "Biaya Kirim";
    $act = "modules/dir-biaya-kirim/do_task.php";

    $getpage = "data-biaya-kirim";
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
                            <th>Ekspedisi</th>
                            <th>Provinsi</th>
                            <th>Kabupaten</th>
                            <th>Via Udara</th>
                            <th>Via Darat</th>
                            <th>Via Laut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT bk.biaya_id, e.ekspedisi_nama, p.nama_provinsi, k.nama_kabupaten, bk.biaya_via_udara, "
                                    . "bk.biaya_via_darat, bk.biaya_via_laut FROM biaya_kirim AS bk "
                                    . "INNER JOIN kabupaten AS k ON bk.id_kabupaten = k.id_kabupaten "
                                    . "INNER JOIN provinsi AS p ON k.id_provinsi = p.id_provinsi "
                                    . "INNER JOIN ekspedisi AS e ON bk.ekspedisi_id = e.ekspedisi_id ";
                            $results = $database->get_results( $query );
                            $no = 1;
                            foreach( $results as $row )
                            {
                                $b_udara = format_IDR($row["biaya_via_udara"]);
                                $b_darat = format_IDR($row["biaya_via_darat"]);
                                $b_laut = format_IDR($row["biaya_via_laut"]);
                                echo "<tr>";
                                    echo "<td>
                                            <a href='?page=$getpage&act=edit&key=$row[biaya_id]'><i class='fa fa-edit'></i> Edit</a> | 
                                            <a href='$act?page=$getpage&act=delete&key=$row[biaya_id]'><i class='fa fa-trash'></i> Delete</a>
                                        </td>";
                                    echo "<td>$row[ekspedisi_nama]</td>";
                                    echo "<td>$row[nama_provinsi]</td>";
                                    echo "<td>$row[nama_kabupaten]</td>";
                                    echo "<td>Rp. $b_udara</td>";
                                    echo "<td>Rp. $b_darat</td>";
                                    echo "<td>Rp. $b_laut</td>";
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
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Data Form</h5>
            </div>
            <div class="panel-body">
                <form role="form" class="form-horizontal" method="POST" action="<?php echo $act.'?page='.$getpage;?>&act=save">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ekspedisi</label>
                        <div class="col-sm-4">
                            <select name="fekspedisi" class="form-control" id="fekspedisi">
                                <?php
                                    $query = "SELECT * FROM ekspedisi ORDER BY ekspedisi_nama";
                                    $results = $database->get_results( $query );
                                    foreach( $results as $row )
                                    {
                                        echo "<option value='$row[ekspedisi_id]'>$row[ekspedisi_nama]</option>";
                                    }
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Provinsi</label>
                        <div class="col-sm-6">
                            <select name="fprovinsi" class="form-control" id="fprovinsi">
                                <option value="">Pilih Provinsi</option>
                                <?php
                                    $query = "SELECT * FROM provinsi ORDER BY nama_provinsi";
                                    $results = $database->get_results( $query );
                                    foreach( $results as $row )
                                    {
                                        echo "<option value='$row[id_provinsi]'>$row[nama_provinsi]</option>";
                                    }
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Kabupaten</label>
                        <div class="col-sm-6">
                            <select name="fkabupaten" class="form-control" id="fkabupaten">
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Biaya Via Udara</label>
                        <div class="col-sm-3">
                            <input type="number" name="fudara" class="form-control" id="fudara" min="0" value="10000" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Biaya Via Darat</label>
                        <div class="col-sm-3">
                            <input type="number" name="fdarat" class="form-control" id="fdarat" min="0" value="10000" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Biaya Via Laut</label>
                        <div class="col-sm-3">
                            <input type="number" name="flaut" class="form-control" id="flaut" min="0" value="10000" required="true">
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
$query = "SELECT * FROM biaya_kirim WHERE biaya_id = '$key' ";
if( $database->num_rows( $query ) > 0 )
{
    list( $id, $kabupaten, $ekspedisi, $b_udara, $b_darat, $b_laut ) = $database->get_row( $query );
?>
<div class="row">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Data Form</h5>
            </div>
            <div class="panel-body">
                <form role="form" class="form-horizontal" method="POST" action="<?php echo $act.'?page='.$getpage;?>&act=update">
                <input type="hidden" name="fid" value="<?php echo $id;?>" readonly>                
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ekspedisi</label>
                        <div class="col-sm-4">
                            <select name="fekspedisi" class="form-control" id="fekspedisi">
                                <?php
                                    $query = "SELECT * FROM ekspedisi ORDER BY ekspedisi_nama";
                                    $results = $database->get_results( $query );
                                    foreach( $results as $row )
                                    {
                                        if($row["ekspedisi_id"] == $ekspedisi){
                                            echo "<option value='$row[ekspedisi_id]' selected disabled>$row[ekspedisi_nama]</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Kabupaten</label>
                        <div class="col-sm-6">
                            <select name="fkabupaten" class="form-control" id="fkabupaten">
                                <?php
                                    $query = "SELECT * FROM kabupaten";
                                    $results = $database->get_results( $query );
                                    foreach( $results as $row )
                                    {
                                        if($row["id_kabupaten"] == $kabupaten){
                                            echo "<option value='$row[id_kabupaten]' selected disabled>$row[nama_kabupaten]</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Biaya Via Udara</label>
                        <div class="col-sm-3">
                            <input type="number" name="fudara" class="form-control" id="fudara" min="0" value="<?php echo $b_udara; ?>" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Biaya Via Darat</label>
                        <div class="col-sm-3">
                            <input type="number" name="fdarat" class="form-control" id="fdarat" min="0" value="<?php echo $b_darat; ?>" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Biaya Via Laut</label>
                        <div class="col-sm-3">
                            <input type="number" name="flaut" class="form-control" id="flaut" min="0" value="<?php echo $b_laut; ?>" required="true">
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

<script type="text/javascript">
    function load_kabupaten(){
        $('#fprovinsi').on('change',function(){
            var idProv = $(this).val();
            if(idProv){
                $.ajax({
                    type:'GET',
                    url:'../json_rsc/ajaxData.php?act=get_kabupaten',
                    data:'key='+idProv,
                    success:function(html){
                        $('#fkabupaten').html(html);
    //                    $('#fkabupaten').html('<option value="">Pilih Kabupaten</option>'); 
                    }
                }); 
            }else{
                $('#fkabupaten').html('<option value="">Pilih Kabupaten</option>');
            }
        });
    }
</script>