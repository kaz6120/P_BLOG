<?php
/**
 * Index File of Include Directory
 *
 * $Id: admin/index.php, 2004/05/23 00:48:55 Exp $
 */

require_once "./user_config.inc.php";

// Deny access to this directory
header("HTTP/1.0 404 Not Found");
header("HTTP/1.1 301 Moved Permanently");
header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
echo 'hello, world! :-P';

?>
