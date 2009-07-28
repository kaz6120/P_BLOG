<?php
/**
 * Logout from P_ANAMA Access Analyzer directory
 *
 * $Id: analyze/logout.php, 2004/06/02 13:11:25 Exp $
 */

$cd = "..";
require_once $cd . '/include/config.inc.php';

if ($cfg['use_session_db'] == 'yes') {
    require_once "{$cd}/{$admin_dir}/db_session.php";
} else {
    session_name($cfg['p_blog_sess_name']);
    session_start();
}

// Initialize session variables
$_SESSION['admin_login'] = 0;
$_SESSION['user_name']   = 0;
$_SESSION['user_pass']   = 0;

session_unset();
session_destroy();
header("HTTP/1.1 301 Moved Permanently");
header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
?>