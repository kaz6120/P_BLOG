<?php
/**
 * Logout from Admin Page to Root Admin (MySQL User) Page
 *
 * $Id: admin/logout_to_root.php, 2004/10/03 14:03:18 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';

if ($cfg['use_session_db'] == 'yes') {
    require_once './db_session.php';
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

if (($cfg['root_path'] == '/path/to/p_blog/') || (is_null($cfg['root_path']))) {
    $request_uri = $http . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
    $uri = parse_url($request_uri);
    $uri = str_replace('go_to_root.php', 'root/root_login.php', $uri);
    header('Location: ' . $http . '://' . $uri['host'] . $uri['path']);
    exit;
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir. '/root/root_login.php');
    exit;
}
?>