<?php
/**
 * Output Configuration
 *
 * $Id: include/config.inc.php, 2004/09/20 00:32:35 Exp $
 */

if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}

if (!extension_loaded('mbstring')) {
    include_once $cd . '/include/mb_emulator/mb-emulator.php';
}

/**
 * Include user config file and main functions
 */
require_once $cd . '/include/user_config.inc.php';
require_once $cd . '/include/fnc_base.inc.php';
require_once $cd . '/include/constants.inc.php';


/**
 * Connect to MySQL
 */
db_connect();


/**
 * Config
 */
$cfg = init_config();



/**
 * Select HTTP / HTTPS
 */
$http = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http');



/**
 * Debug
 */
if ($cfg['debug_mode'] == 'on') {
    error_reporting(E_ALL);
}


/**
 * Start Benchmark
 */
if (isset($cfg['show_generation_time']) == 'yes') {
    $begin_time_str = microtime();
}

?>