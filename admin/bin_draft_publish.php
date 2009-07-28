<?php
/**
 * Publish Draft File
 *
 * $Id: admin/updated.php, 2004/12/16 06:37:41 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/files/include/fnc_files.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_POST['draft'], $_POST['id'], $_POST['bin_mod']) &&
       (intval($_POST['draft']) == 0)) {
        $id      = $_POST['id'];
        $bin_mod = $_POST['bin_mod'];
        $sql = 'UPDATE ' . $info_table . " SET `draft` = '0', `bin_mod` = '" . $bin_mod . "' WHERE `id` = '" . $id . "'";
        $res = mysql_query($sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
        if ($res) {
            $contents = '<div class="section">'."\n".
                        '<h2 class="archive-title">'.$lang['file_published']."</h2>\n".
                        "</div>\n";
        }

        // Next, pull out the data and display the preview.
        $sql  = 'SELECT ' .
                "`id`, `bin_title`, `bintype`, `binname`, `binsize`, `bindate`, ".
                "DATE_FORMAT(`bin_mod`, '%Y-%m-%d %T') as `bin_mod`, `bin_category`, `bincomment`, `bin_count`, `draft`".
                ' FROM ' . $info_table .
                " WHERE `id` = '{$id}'";
        $res  = mysql_query($sql);
        $row  = mysql_fetch_array($res);
        
        // Generate XHTML
        $row = convert_to_utf8($row);
        format_date($row_name = 'bindate');
        $title_date = $formatted_date;
        $contents .= '<div class="section">'."\n".
                     '<h2 class="date-title">'.$title_date."</h2>\n";
        $contents .= display_binary_box($row);
        $contents .= file_uploaded();
        $contents .= "</div><!-- End .section -->\n";

        xhtml_output('');

    } else{ // if user auth failed...
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
