<?php
/**
 * Update Logs
 *
 * $Id: admin/update.php, 2004/12/29 17:55:47 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';
require_once $cd . '/files/include/fnc_files.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

if (($session_status == 'on') && (isset($_POST['mode']))) {
    switch ($_POST['mode']) {
        //-------------------- SWITH TO MODIFY-BINARY MODE -------------------------
        case 'bin':
            if (!empty($_POST['id'])) {
                $id = $_POST['id'];
                //query
                $sql  = "SELECT * FROM {$info_table} WHERE id='{$id}'";
                $res = mysql_query($sql);
                $row = mysql_fetch_array($res);       
                if ($row) {
                    $row = convert_to_utf8($row);
                    $contents = update_bin_form('');
                } else {
                    $contents = '<h3>'.$lang['cant_find'].'</h3>';
                }
            } else {
                    $contents = no_id_error();
            }
            break;
        // in case posted "add" parameter is "log"
        //-------------------- SWITCH TO MODIFY-LOG MODE -------------------------
        default:
            if (!empty($_POST['id'])) {
                $id = $_POST['id'];
                $sql = 'SELECT * FROM ' . $log_table . ' WHERE id = ' . $id;
                $res = mysql_query($sql);
                $row = mysql_fetch_array($res);
                if ($row) {
                    $row = convert_to_utf8($row);
                    $contents = update_log_form('');
                } else {
                    $contents = '<h2>'.$lang['cant_find'].'</h2>';
                }
            } else {
                $contents = no_id_error();
            }
            break;
    }// end of switch
    
    xhtml_output('');
    
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
