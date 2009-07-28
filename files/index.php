<?php
/**
 * Binary File Downloader Index
 *
 * $Id: files/index.php, 2005/01/07 16:56:39 Exp $
 */


$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_files.inc.php';

session_control();

// SQL : submit data sort query
$sort_sql  = 'SELECT '.
             "`id`, `bin_title`, `bintype`, `binname`, `binsize`, `bindate`, DATE_FORMAT(`bin_mod`, '%Y-%m-%d %T') as `bin_mod`, `bin_category`, `bincomment`, `bin_count`, `draft`".
             ' FROM ' . $info_table .
             " WHERE `draft` = '0'".
             " ORDER BY `bindate` DESC LIMIT " . $cfg['pagemax'];
$res  = mysql_query($sort_sql);
if (!$res) {
    die(mysql_error());
} else {
    $rows = mysql_num_rows($res);
}
// Contents
if ($rows) {
    if ($cfg['show_file_date_title'] == 'yes') { 
        $row = mysql_fetch_array($res);
        format_date($row_name = 'bindate');
        $title_date = $formatted_date;
        if ($cfg['use_2_indexes'] == 'yes') {
            $indexes = '<p class="flip-link"><strong>'.$lang['by_date'].'</strong> <a href="./index2.php">'.$lang['by_category'].'</a></p>';
        } else {
            $indexes = '';
        }
        $contents =<<<EOD
<div class="section">
<h2 id="archive-title">{$cfg['file_index_title']}</h2>
{$indexes}
</div>
<div class="section">
<h2 class="date-title">{$title_date}</h2>
EOD;
        do { 
            // $tmp_date = substr($row['bindate'], 0, 10);
            format_date($row_name = 'bindate');
            $tmp_date = $formatted_date;
            if ($title_date != $tmp_date) { 
                $title_date = $tmp_date;
                $contents .= '</div><!-- End .section -->'."\n\n".
                             '<div class="section">'."\n".
                             '<h2 class="date-title">'.$title_date."</h2>\n";
            }
            $row = convert_to_utf8($row);
            $contents .= display_binary_box($row);
        } while ($row = mysql_fetch_array($res));
        
        $contents .= "</div><!-- End .section -->\n\n";
        $contents .= display_prev_logs_navi('files/search');
        
    } else {
        $contents = "\n".
                    '<div class="section">'."\n".
                    '<h2>'.$lang['recent'].'<strong>'.$rows.'</strong>'.$lang['files']."</h2>\n";
        while ($row = mysql_fetch_array($res)) {
            $row = convert_to_utf8($row);
            $contents .= display_binary_box($row);
        }
        
        $contents .= "</div><!-- End .section -->\n\n";
        $contents .= display_prev_logs_navi('files/search');
    }
} else {
    $contents = "\n".
                '<div class="section">'."\n".
                '<h2>Welcome to ' . $cfg['blog_title'] . " !</h2>\n".
                '<p>' . $lang['no_files'] . "</p>\n".
                "</div>\n";
}

xhtml_output('file');

?>
