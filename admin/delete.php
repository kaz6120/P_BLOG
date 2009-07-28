<?php
/**
 * Delete Logs From MySQL
 *
 * $Id: admin/delete.php, 2004/10/14 00:11:48 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if ($_POST['id'] == '') {
         header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
         exit;
    } else {
        $id = intval($_POST['id']);
        // submit delete query
        $sql = 'DELETE FROM ' . $log_table . " WHERE id='" . $id . "'";
        mysql_query($sql) or die("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
        
        $contents =<<<EOD
<div class="section">
<h2>{$lang['log']} ID:{$id}{$lang['deleted']}</h2>
<h3 class="ref"><a href="{$cd}/index.php" accesskey="c">{$lang['check_index']}</a></h3>
</div>
EOD;

        xhtml_output('log');
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
