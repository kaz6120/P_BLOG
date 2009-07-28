<?php
/**
 * Check Added Log
 *
 * $Id: admin/check.php, 2005/01/07 16:51:38 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_individual.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

// switch blog mode and binary mode
if ($session_status == 'on') {
    if (isset($_REQUEST['id']) && $_REQUEST['id'] !== NULL) {
        $id = $_REQUEST['id'];
        // query to pull out the recent articles.
        $sql = 'SELECT'.
               " `id`, `href`, `name`, `date`, DATE_FORMAT(`mod`, '%Y-%m-%d %T') as `mod`, `comment`, `category`, `draft`".
               " FROM `{$log_table}` WHERE `id` = '{$id}'";
        $res = mysql_query($sql);
        $row = mysql_fetch_array($res);
        $row = convert_to_utf8($row);

        if ($cfg['show_date_title'] == 'yes') {
            format_date($row_name = 'date');
            $title_date = $formatted_date;
            $date_section_begin  = '<div class="section">'."\n".
                                   '<h2 class="date-title">'.$title_date."</h2>\n";
            $date_section_end    = "\n</div><!-- End .section -->";
        } else {
            $date_section_begin = '';
            $date_section_end   = '';
        }
        
        $contents  = $date_section_begin;
        $contents .= display_article_box($row);
        $contents .= $date_section_end;
    } else {
        $contents = display_by_id_form('article');
    }
    
    xhtml_output('');
    
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
