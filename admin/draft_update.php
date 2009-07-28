<?php
/**
 * Update Draft Log
 *
 * $Id: admin/draft_update.php, 2004/12/29 17:56:18 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

if (($session_status == 'on') && (isset($_POST['mode']))) {
    if ($_POST['id'] != "") {
        $id = $_POST['id'];
        // Query
        $sql = "SELECT * FROM `{$log_table}` WHERE `id` = '{$id}'";
        $res = mysql_query($sql);
        $row = mysql_fetch_array($res);
        if ($row) {
            $row = convert_to_utf8($row);
            $contents = update_log_form('draft');
            
        } else {
            $contents = '<h2>'.$lang['cant_find'].'</h2>';
        }
    } else {
        $contents = no_id_error();
    }
    
    xhtml_output('');
    
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
