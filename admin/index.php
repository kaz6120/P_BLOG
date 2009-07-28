<?php
/**
 * Index of Admin Directory
 *
 * $Id: admin/index.php, 2004/06/02 13:06:19 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';

header("HTTP/1.0 404 Not Found");
header("HTTP/1.1 301 Moved Permanently");
header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
exit;
?>
