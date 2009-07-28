<?php
/**
 * Logout from Root User Directory to Admin Main
 *
 * $Id: admin/root/logout.php, 2004/06/02 13:06:56 Exp $
 */

$cd = '../..';
require_once $cd . '/include/config.inc.php';

if ($cfg['use_session_db'] == 'yes') {
    require_once '../db_session.php';
} else {
    session_name($cfg['p_blog_root_sess_name']);
    session_start();
}

// Initialize session variables
$_SESSION['root_admin_login'] = 0;
$_SESSION['root_user_name']   = 0;
$_SESSION['root_user_pass']   = 0;

session_unset();
session_destroy();

header("HTTP/1.1 301 Moved Permanently");
header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir. '/login.php');
?>