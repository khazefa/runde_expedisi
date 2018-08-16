<?php
require("../includes/constants.php");

function anti_injection($data){
    $filter = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
    return $filter;
}

$username = anti_injection($_POST['username']);
$pass     = anti_injection(md5($_POST['password']));

// pastikan username dan password adalah berupa huruf atau angka.
if (!ctype_alnum($username) OR !ctype_alnum($pass)){
    header('HTTP/1.1 403 Forbidden.', TRUE, 403);
    echo 'You dont have permissions to access this page! <a href="javascript:history.back()">Back</a>';
    exit(1); // EXIT_ERROR
}else{
    
    require_once('../includes/class.db.php');

    $database = DB::getInstance();

    $query = "SELECT user_keyname, user_fullname, user_email FROM users WHERE user_keyname='$username' "
            . "AND user_keypass='$pass' AND level_id = 1";
    if( $database->num_rows( $query ) > 0 )
    {
        list( $ukey, $uname, $umail ) = $database->get_row( $query );
        session_start();

        $_SESSION['isLoggedin']	= TRUE;
        $_SESSION['vUser']	= $ukey;
        $_SESSION['vName']	= $uname;
        $_SESSION['vMail']	= $umail;
        header('location:'.$baseurl);
    }
    else
    {
        header('HTTP/1.1 403 Forbidden.', TRUE, 403);
        echo 'Your username or password mismatch! <a href="javascript:history.back()">Back</a>';
        exit(1); // EXIT_ERROR
    }
}
?>
