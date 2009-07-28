<?php
/**
 * Add New Log or Upload New Binary File
 *
 * $Id: admin/add.php, 2004/10/04 22:22:26 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';
require_once $cd . '/files/include/fnc_files.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_GET['add']) && ($_GET['add'] != '')) {
        switch($_GET['add']) {
            case 'bin':
                $contents = display_up_file_form();
                break;
            case 'log':
                $contents = display_add_log_form($post_password = '', $post_username = '', $text_cols);
                break;
            default:
                $contents = display_add_log_form($post_password = '', $post_username = '', $text_cols);                
                break;
        }
        
        xhtml_output('');
        
    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>