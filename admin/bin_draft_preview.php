<?php
/**
 * Draft File Preview
 *
 * $Id: admin/peview.php, 2004/11/24 01:29:04 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/files/include/fnc_files.inc.php';
require_once $cd . '/include/fnc_individual.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        // Next, pull out the data and display the preview.
        $sql  = 'SELECT ' .
                "`id`, `bin_title`, `bintype`, `binname`, `binsize`, `bindate`, ".
                "DATE_FORMAT(`bin_mod`,'%Y-%m-%d %T') as `bin_mod`, `bin_category`, `bincomment`, `bin_count`, `draft`".
                ' FROM ' . $info_table .
                " WHERE `id` = '{$id}'";
        $res  = mysql_query($sql);
        $row  = mysql_fetch_array($res);
        $row = convert_to_utf8($row);
        
        if ($cfg['show_date_title'] == 'yes') {
            format_date($row_name = 'bindate');
            $title_date = $formatted_date;
            $date_section_begin  = '<div class="section">'."\n".
                                   '<h2 class="date-title">'.$title_date."</h2>\n";
            $date_section_end    = "\n</div><!-- End .section -->";
        } else {
            $date_section_begin = '';
            $date_section_end   = '';
        }
        
        $contents  = $date_section_begin;
        $contents .= display_binary_box($row);

        // Reformat the modification time to "yyyymmddhms"
        $cmod  = preg_replace("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", "$1$2$3$4$5$6", $row['bin_mod']);

        $contents .=<<<EOD

<form method="post" action="./bin_draft_publish.php">
<div class="submit-button">
<input type="hidden" name="draft" value="0" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="bin_mod" value="{$cmod}" />
<input type="submit" value="{$lang['publish']}" />
</div>
</form>
EOD;
        $contents .= file_uploaded();
        $contents .= $date_section_end;
        
        xhtml_output('');
        
    } else{
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
