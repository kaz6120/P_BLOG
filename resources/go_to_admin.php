<?php
/**
 * Logout from resources directory
 *
 * $Id: resources/go_to_admin.php, 2004/06/01 11:04:32 Exp $
 */
$cd = '..';
require_once $cd . '/include/config.inc.php';

session_control();

// if on-session, let him or her go to the admin index page
if ($session_status == 'on') {
    // Initialize session variables
    $_SESSION['admin_login'] = 0;
    $_SESSION['user_name']   = 0;
    $_SESSION['user_pass']   = 0;
    session_unset();
    session_destroy();
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir. '/login.php');
    exit;
// if off-session, let him or her go to the index page
} else {
    header("HTTP/1.0 404 Not Found");
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}

?>