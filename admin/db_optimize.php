<?php
/**
 * Optimize Tables
 *
 * $Id: admin/db_optimize.php, 2004/12/29 22:54:15 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_GET['table_name'])) {

        $contents =<<<EOD

<h2>{$lang['system_admin']}</h2>
<ul class="flip-menu">
<li><a href="./admin_top.php">{$lang['sys_env']}</a></li>
<li><a href="./preferences.php">{$lang['preferences']}</a></li>
<li><a href="./edit_menu.php">{$lang['edit_custom_file']}</a></li>
<li><a href="./db_status.php">{$lang['db_table_status']}</a></li>
</ul>
<form method="post" name="dbtables" action="./db_backup.php">
<p>
<input type="hidden" name="DBbackup" value="yes" />
</p>
</form>
EOD;

        $table_name = $_GET['table_name'];
        
        // Optimize table
        $optimize_sql = 'OPTIMIZE TABLE `'.$table_name.'`';
        $res = mysql_query($optimize_sql);
        if ($res) {
            $contents .= '<h3>' . $lang['optimized_msg_1'] . $table_name . $lang['optimized_msg_2'] . "</h3>\n";
        } else {
            $contents .= '<h3>No Table Optimized</h3>';
        }

        xhtml_output('');

    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else { // If out of session...
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
