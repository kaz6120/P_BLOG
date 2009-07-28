<?php
/**
 * Check Uploaded File
 *
 * $Id: admin/bin_check.php, 2005/01/07 16:52:50 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_individual.inc.php';
require_once $cd . '/files/include/fnc_files.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

// switch blog mode and binary mode
if ($session_status == 'on') {
    if (isset($_REQUEST['id']) && $_REQUEST['id'] !== NULL) {
        $id = $_REQUEST['id'];
        $sql  = "SELECT ".
                "`id`, `bin_title`, `bintype`, `binname`, `binsize`, `bindate`, ".
                "DATE_FORMAT(`bin_mod`,'%Y-%m-%d %T') as `bin_mod`, `bin_category`, `bincomment`, `bin_count`, `draft`".
                ' FROM ' . $info_table .
                " WHERE `id` = '{$id}'";
        $res = mysql_query($sql);
        $row = mysql_fetch_array($res);
        $row = convert_to_utf8($row);
        $contents  = '<div class="section">'.
                     "\n<h2>".$lang['file'].' ID : '.$id."</h2>\n";
        $contents .= display_binary_box($row, $cfg['show_md5'], $data_table);
        $contents .= "</div>\n";

    } else {
        $contents = display_by_id_form('article');
    }

    xhtml_output('');

} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
