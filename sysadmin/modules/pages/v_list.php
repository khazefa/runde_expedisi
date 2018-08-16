<?php
error_reporting(0);
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    $pagetitle = "Konten Website";
    $act = "modules/pages/do_task.php";

    $getpage = "list-pages";
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
                <span class="pull-right"><button class="btn btn-primary" onclick="location.href='?page=<?php echo $getpage; ?>&act=add';"><i class="fa fa-plus-circle"></i> Add New</button> </span>
            </div>
            <div class="panel-body">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Title</th>
                            <th>Url Slug</th>
                            <th>Publish</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT * FROM site_pages";
                            $results = $database->get_results( $query );
                            $no = 1;
                            foreach( $results as $row )
                            {
                                $publish = $row[pg_publish] == "Y" ? "Yes" : "No";
                                echo "<tr>";
                                    echo "<td>
                                            <a href='?page=$getpage&act=edit&key=$row[pg_id]'><i class='fa fa-edit'></i> Edit</a> | 
                                            <a href='$act?page=$getpage&act=delete&key=$row[pg_id]'><i class='fa fa-trash'></i> Delete</a>
                                        </td>";
                                    echo "<td>$row[pg_title]</td>";
                                    echo "<td>$row[pg_slug]</td>";
                                    echo "<td>$publish</td>";
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
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" name="ftitle" class="form-control" id="ftitle" placeholder="Page Title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Content</label>
                        <div class="col-sm-10">
                            <textarea name="fcontent" class="form-control summernote" id="fcontent" placeholder="Page Content"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Publish</label>
                        <div class="col-sm-2">
                            <select name="fpublish" class="form-control" id="fpublish">
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
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
$query = "SELECT * FROM site_pages WHERE pg_id = '$key' ";
if( $database->num_rows( $query ) > 0 )
{
    list( $id, $title, $slug, $content, $publish ) = $database->get_row( $query );
    $Y = $publish == "Y" ? "selected" : "";
    $N = $publish == "N" ? "selected" : "";
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
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" name="ftitle" class="form-control" id="ftitle" value="<?php echo $title;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Url Slug</label>
                        <div class="col-sm-10">
                            <input type="text" name="fslug" class="form-control" id="fslug" value="<?php echo $slug;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Content</label>
                        <div class="col-sm-10">
                            <textarea name="fcontent" class="form-control summernote" id="fcontent"><?php echo $content;?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Publish</label>
                        <div class="col-sm-2">
                            <select name="fpublish" class="form-control" id="fpublish">
                                <option value="Y" <?php echo $Y;?>>Yes</option>
                                <option value="N" <?php echo $N;?>>No</option>
                            </select>
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