<?php
/**
 * RSS link - delete
 *
 * $Id: delete.php, 2004/05/02 01:19:11 Exp $
 */

//require_once '../lib/php/XML/RSS.php';
$cd = '../../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_POST['rss_id'])) {
        $id  = intval($_POST['rss_id']);
        $sql = 'DELETE FROM `p_rss_box` WHERE r_id = ' . $id;
        $res = mysql_query($sql);
        if ($res) {
            header('Location: ./modify.php');
            exit;
        } 
    }
} else {
    bad_req_error();
    exit;
}
footer();
?>
