<?php
/**
 * Insert The Access Logs Into MySQL
 *
 * $Id: analyze/env_info_insert.php, 2003/12/04 14:43:09 Exp $
 */

require_once 'include/config.inc.php';

if (!isset($_SERVER['HTTP_REFERER'])) {
    $ref = 'Direct or Unknown';
} else {
    $ref = $_SERVER['HTTP_REFERER'];
    urldecode($ref);
}

// get user agent info
//----------------------------------
$browser = $_SERVER['HTTP_USER_AGENT'];
urldecode($browser);

// get remote host info
//----------------------------------
if (!isset($_SERVER['REMOTE_HOST'])) {
    $_SERVER['REMOTE_HOST'] = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $re_host = $_SERVER['REMOTE_HOST'];
} else {
    $re_host = $_SERVER['REMOTE_HOST'];
}

// insert client's info into database
$sql = 'INSERT INTO '.
       $analyze_table . '(ref, browser, re_host, date)'.
       " VALUES('" . $ref . "', '" . $browser . "', '" . $re_host . "', now())";
$results = mysql_query($sql);

?>
