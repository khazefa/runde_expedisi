<?php
error_reporting(0);
$isLoggedIn = $_SESSION['isLoggedin'];

if(!isset($isLoggedIn) || $isLoggedIn != TRUE){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    $pagetitle = "User List";
    $act = "modules/users/do_task.php";

    $getpage = "user-list";
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
                <!--<span class="pull-left"><button class="btn btn-warning" onclick="location.href='?page=level-list';"><i class="fa fa-list"></i> User Level</button> </span>-->
                <span class="pull-right"><button class="btn btn-primary" onclick="location.href='?page=<?php echo $getpage; ?>&act=add';"><i class="fa fa-plus-circle"></i> Add New</button> </span>
            </div>
            <div class="panel-body">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Full Name</th>
                            <th>User Access</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT u.user_keyname, u.user_fullname, ul.level_name, u.user_status "
                                    . "FROM users AS u INNER JOIN users_level AS ul ON u.level_id = ul.level_id "
                                    . "WHERE u.level_id <> 1";
                            $results = $database->get_results( $query );
                            $no = 1;
                            foreach( $results as $row )
                            {
                                echo "<tr>";
                                    echo "<td>
                                            <a href='?page=$getpage&act=edit&key=$row[user_keyname]'><i class='fa fa-edit'></i> Edit</a> | 
                                            <a href='$act?page=$getpage&act=delete&key=$row[user_keyname]'><i class='fa fa-trash'></i> Delete</a>
                                        </td>";
                                    echo "<td>$row[user_fullname]</td>";
                                    echo "<td>". strtoupper($row[level_name]) ."</td>";
                                    echo "<td>".$status = $row[user_status] == 1 ? 'Active':'Non Active'."</td>";
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
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" name="funame" class="form-control" id="funame" placeholder="Username" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" name="fpass" class="form-control" id="fpass" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="fname" class="form-control" id="fname" placeholder="Full Name" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="femail" class="form-control" id="femail" placeholder="Email" required="true">
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
$query = "SELECT user_id, user_keyname, user_fullname, user_email, user_status FROM users WHERE user_keyname = '$key' ";
if( $database->num_rows( $query ) > 0 )
{
    list( $id, $keyname, $name, $email, $status ) = $database->get_row( $query );
    $active = $status == 1 ? "selected" : "";
    $inactive = $status == 0 ? "selected" : "";
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
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" name="funame" class="form-control" id="funame" value="<?php echo $keyname;?>" readonly="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" name="fpass" class="form-control" id="fpass">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="fname" class="form-control" id="fname" value="<?php echo $name;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="femail" class="form-control" id="femail" value="<?php echo $email;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10">
                            <select name="fstatus" class="form-control" id="fstatus">
                                <option value=1 <?php echo $active;?>>Active</option>
                                <option value=0 <?php echo $inactive;?>>Non Active</option>
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