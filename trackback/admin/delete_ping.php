<?php
/**
 * Delete Trackback Ping URI
 *
 * $Id: trackback/admin/delete_ping.php, 2004/05/15 18:33:41 Exp $
 */

$cd = '../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';

session_control();

if ($session_status == 'on') {
    if (($_POST['ping_id'] == '') || ($_POST['id'] == '')) {
         header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
         exit;
    } else {
        $ping_id = intval($_POST['ping_id']);
        // submit delete query
        $sql = 'DELETE FROM ' . $trackback_table . " WHERE id = '" . $ping_id . "'";
        $res = mysql_query($sql) or die("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
        if ($res) {
            header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'article.php?id=' . intval($_POST['id']));
        }
    }
} else {
     header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
     exit;
}
?>