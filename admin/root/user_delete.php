<?php
/**
 * Delete User Account
 *
 * $Id: admin/root/user_delete.php, 2005/02/05 14:15:06 Exp $
 */


$cd = '../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once '../include/fnc_admin.inc.php';

if ($cfg['use_session_db'] == 'yes') {
    require_once '../db_session.php';
} else {
    session_name($cfg['p_blog_root_sess_name']);
    session_start();
}

if (isset($_SESSION['root_admin_login'], $_SESSION['root_user_name'], $_SESSION['root_user_pass'], $_POST['user_id'])) {
    if (($_SESSION['root_user_name'] == $user) && ($_SESSION['root_user_pass'] == md5($password))) {
        if ($_SESSION['root_admin_login'] == '') {
            $_SESSION['root_user_name']   = $mysql_user;
            $_SESSION['root_user_pass']   = $mysql_pass;
            $_SESSION['root_admin_login'] = TRUE;
        }
        if ($_SESSION['root_admin_login'] == 1) {
            $user_id = intval($_POST['user_id']);
            $sql = "DELETE FROM `" . $user_table . "` WHERE `user_id` = '" . $user_id . "'";
            $res = mysql_query($sql) or die("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
            if ($res) {
                $contents = '<h2 id="account-manager">'.$lang['account_deleted'].'</h2>'."\n".
                            '<p>'.$lang['account_deleted_msg'].'</p>'."\n".
                            '<p class="ref"><a href="./user_list.php">'.$lang['user_list'].'</a></p>'."\n".
                            '<p class="ref"><a href="./user_regist.php">'.$lang['create_accounts']."</a></p>\n";
            } else {
                $contents = wrong_user_error();
            }
        } else {
            $contents = wrong_user_error();
        }
    } else {
        $contents = wrong_user_error();
    }
    
    $admin = 'yes';
    
    xhtml_output('');

} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
