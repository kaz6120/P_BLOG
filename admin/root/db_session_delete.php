<?php
/**
 * Delete User Session Files
 *
 * $Id: admin/root/db_session_delete.php, 2005/02/05 14:24:11 Exp $
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

if (isset($_SESSION['root_admin_login'], $_SESSION['root_user_name'], $_SESSION['root_user_pass'])) {
    if (($_SESSION['root_user_name'] == $user) && ($_SESSION['root_user_pass'] == md5($password))) {
        if ($_SESSION['root_admin_login'] == '') {
            $_SESSION['root_user_name']   = $mysql_user;
            $_SESSION['root_user_pass']   = $mysql_pass;
            $_SESSION['root_admin_login'] = TRUE;
        }
        if ($_SESSION['root_admin_login'] == 1) {         
            // force garbage collect
            if ((isset($_POST['sess_gc'])) && ($_POST['sess_gc'] == 'go')) {
                $maxlifetime = get_cfg_var("session.gc_maxlifetime");
                $expiration_time = time() - $maxlifetime;
                $sql = "DELETE FROM `{$session_table}` WHERE `sess_date` < '" . $expiration_time . "'";
            }
            // force delete session by id
            if (isset($_POST['sess_id'])) {
                $sess_id = $_POST['sess_id'];
                $sql = "DELETE FROM `{$session_table}` WHERE `id` = '".$sess_id."'";
            }
            $res = mysql_query($sql) or die("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
            if ($res) {
                $contents =<<<EOD
<h2 id="account-manager">SESSION ID DELETED</h2>
<p>The selected user's session ID has been deleted successfully.</p>
<p class="ref"><a href="./user_list.php">{$lang['user_list']}</a></p>
<p class="ref"><a href="./user_regist.php">{$lang['create_accounts']}</a></p>
<p class="ref"><a href="./db_session_index.php">SESSION-ID LIST</a></p>
EOD;
                // optimize table
                $optimize_sql = "OPTIMIZE TABLE `{$session_table}`";
                mysql_query($optimize_sql) or die(mysql_error());
            } else {
                $contents = wrong_user_error();
            }
        } else {
            $contents = wrong_user_error();
        }
    } else {
        $contents = wrong_user_error();
    }

    xhtml_output('');

} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir. '/login.php');
    exit;
}
?>
