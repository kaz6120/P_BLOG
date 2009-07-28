<?php
/**
 * Delete Uploaded Binary File From MySQL
 *
 * $Id: admin/bin_delete.php, 2004/10/13 23:58:08 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if ($_POST['id'] == "") {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    } else {
        $id = intval($_POST['id']);
        // Submit delete query
        $sql1 = 'DELETE FROM ' . $info_table . " WHERE id='" . $id . "'";
        $sql2 = 'DELETE FROM ' . $data_table . " WHERE masterid='" . $id ."'";
        if (isset($id)) {        
            if (!mysql_query($sql1)) {
                die("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
            }
            if (!mysql_query($sql2)) {
                die("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
            }
            $contents =<<<EOD
<div class="section">
<h2>{$lang['file']} ID:{$id}{$lang['deleted']}</h2>
<h3 class="ref"><a href="{$cd}/files/index.php" accesskey="c">{$lang['check_index']}</a></h3>
</div>
EOD;

        } else {
            $contents = '<div class="section">'."\n".
                        '<h2>' . $lang['del_failed'] . "</h2>\n".
                        "</div>\n";
        }

        xhtml_output('file');

    }
} else {
     header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
     exit;
}
?>
