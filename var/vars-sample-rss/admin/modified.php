<?php
/**
 * RSS link - add
 *
 * $Id: rss/admin/add.php, 2005/01/22 23:29:53 Exp $
 */

//require_once '../lib/php/XML/RSS.php';
$cd = '../../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';


session_control();

against_xss();

if ($session_status == 'on') {
    $sql = 'UPDATE `p_rss_box` '.
           'SET '.
           "r_name='" . $_POST['rss_name'] . "', ".
           "r_uri='" . $_POST['rss_uri'] . "', ".
           "r_category='" . $_POST['rss_category'] ."' ".
           "WHERE r_id='" . $_POST['rss_id'] . "'";
           
    $res  = mysql_query($sql);
    if ($res) {
        header('Location: ./modify.php');
        exit;
    }
} else {
    bad_req_error();
    exit;
}


footer();
?>
