<?php
error_reporting(0);
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    $pagetitle = "Principal";
    $act = "modules/dir-principal/do_task.php";

    $getpage = "data-principal";
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
                            <th>Kode</th>
                            <th>Nama Principal</th>
                            <th>Alias</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT * FROM principal";
                            $results = $database->get_results( $query );
                            $no = 1;
                            foreach( $results as $row )
                            {
                                echo "<tr>";
                                    echo "<td>
                                            <a href='?page=$getpage&act=edit&key=$row[principal_id]'><i class='fa fa-edit'></i> Edit</a> | 
                                            <a href='$act?page=$getpage&act=delete&key=$row[principal_id]'><i class='fa fa-trash'></i> Delete</a>
                                        </td>";
                                    echo "<td>$row[principal_kode]</td>";
                                    echo "<td>$row[principal_nama]</td>";
                                    echo "<td>$row[principal_alias]</td>";
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
                        <label class="col-sm-3 control-label">Kode Principal</label>
                        <div class="col-sm-3">
                            <input type="text" name="fkode" class="form-control" id="fkode" value="<?php echo $kode; ?>" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Principal</label>
                        <div class="col-sm-6">
                            <input type="text" name="fnama" class="form-control" id="fnama" value="<?php echo $nama; ?>" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alias</label>
                        <div class="col-sm-4">
                            <input type="text" name="falias" class="form-control" id="falias" value="<?php echo $alias; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-10">
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
$query = "SELECT * FROM principal WHERE principal_id = '$key' ";
if( $database->num_rows( $query ) > 0 )
{
    list( $id, $kode, $nama, $alias ) = $database->get_row( $query );
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
                        <label class="col-sm-3 control-label">Kode Principal</label>
                        <div class="col-sm-3">
                            <input type="text" name="fkode" class="form-control" id="fkode" value="<?php echo $kode; ?>" readonly="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Principal</label>
                        <div class="col-sm-6">
                            <input type="text" name="fnama" class="form-control" id="fnama" value="<?php echo $nama; ?>" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alias</label>
                        <div class="col-sm-4">
                            <input type="text" name="falias" class="form-control" id="falias" value="<?php echo $alias; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-10">
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