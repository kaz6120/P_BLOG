<?php
/**
 * List Up Binary Files By Category
 *
 * $Id: files/category.php, 2006/01/16 20:53:59 Exp $
 */


$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_files.inc.php';

session_control();

against_xss();

if ((isset($_GET['k'])) || (isset($_GET['p'])) || (isset($_GET['d']) || (isset($_GET['pn'])))) {

    // Simple Query
    // @author: hetima
    // @modify: kaz
    if ((empty($_GET['d'])) && (empty($_GET['p'])) && (empty($_GET['pn'])) && (empty($_GET['c']))) {
        $_GET['d']  = '';
        $_GET['p']  = '0';
        $_GET['pn'] = '1';
        $_GET['c']  = '1';
    }

    
    if ($cfg['enable_unicode'] == 'on') {
        $key = mb_convert_encoding($_GET['k'], $cfg['mysql_lang'], "utf-8");
    } else {
        $key = $_GET['k'];
    }

    $page  = $_GET['p'];
    $date  = $_GET['d'];
    
    //=====================================
    // CATEGORY LIST UP QUERY -- HOW?
    //=====================================
    $patterns = '/(\*|\+|\^|\$|\?|\(|\))/';
    if (preg_match($patterns, $key)) {
        // (1) Use "LIKE" search
        $listup_query = "BINARY `bin_category` LIKE '%".$key."%')";
    } else {
        // (2) Use MySQL Regular Expression
        $listup_query = "`bin_category` REGEXP BINARY '^".$key."$|^".$key.",|,".$key.",|,".$key."$|,?".$key.",|,?".$key."$')";
    }
    
    $sql  = 'SELECT '.
            "`id`, `bin_title`, `bintype`, `binname`, `binsize`, `bindate`, ".
            "DATE_FORMAT(`bin_mod`, '%Y-%m-%d %T') as `bin_mod`, `bin_category`, `bincomment`, `bin_count`, `draft`".
            ' FROM `' . $info_table . "` WHERE (`draft` = '0') AND (" . $listup_query;
            
    if ($date != '') { " AND (`bindate` LIKE '".$date."%')"; }
    $sql .= " ORDER BY `bindate` DESC LIMIT {$page}, {$cfg['pagemax']} ";

    //=======================
    // SUBMIT SEARCH QUERY
    //=======================
    if ($res = mysql_query($sql)) {
        // query results --- count all hit data
        $count_sql = "SELECT `id` FROM `{$info_table}` WHERE (`draft` = '0') AND (" . $listup_query;
        
        if ($date != '') { " AND (`bindate` LIKE '".$date."%')"; }
        
        $hit_res = mysql_query($count_sql);
        $hit_row = mysql_num_rows($hit_res);

        // Show the hit data info.
        $rows = mysql_num_rows($res);
        if ($cfg['enable_unicode'] == 'on') {
            $key = mb_convert_encoding($key, "UTF-8", $cfg['mysql_lang']);
        }
        
        // Category or Tags
        $lang['categ_list'] = ($cfg['category_style'] == 3) ? $lang['tags_list'] : $lang['categ_list'];
        $lang['category']   = ($cfg['category_style'] == 3) ? $lang['tags'] : $lang['category'];
        
        if ($key != '') {
            $archive_title = $lang['category'].' : '.htmlspecialchars(stripslashes($key));
        }
        
        $disp_page = $page + 1;
        $disp_rows = $page + $rows;
        $hit_result =<<<EOD
<div class="section">
<h2 id="archive-title">{$archive_title}</h2>
<p class="search-res">
{$lang['categ_list']}<span class="search-res">{$hit_row}</span>{$lang['hit_msg']} 
<span class="search-res">{$disp_page} - {$disp_rows}</span> / <span class="search-res">{$hit_row}</span>
</p>
</div><!-- End .section -->
EOD;
        
        //=======================
        // SHOW THE RESULTS!
        //=======================
        if ($hit_row) {
            $flip_link = display_page_flip($field = 'bin_category', $keyword = $key, $case = '1');
            if ($cfg['show_file_date_title'] == 'yes') { 
                $row = mysql_fetch_array($res);
                format_date($row_name = 'bindate');
                $title_date = $formatted_date;
                $section_content = '<h2 class="date-title">'.$title_date."</h2>\n";
                do { 
                    format_date($row_name = 'bindate');
                    $tmp_date = $formatted_date;
                    if ($title_date != $tmp_date) { 
                        $title_date = $tmp_date;
                        $section_content .= '</div><!-- End .section -->'."\n\n".
                                            '<div class="section">'."\n".
                                            '<h2 class="date-title">'.$title_date."</h2>\n";
                    }
                    $row = convert_to_utf8($row);
                    $section_content .= display_binary_box($row, $data_table);
                } while ($row = mysql_fetch_array($res));                
            } else {
                $section_content = '';
                while ($row = mysql_fetch_array($res)) {
                    $row = convert_to_utf8($row);
                    $section_content .= display_binary_box($row, $data_table);
                }
            }
        } else {
            $flip_link = '';
            $section_content = '<h2>'.$lang['no_matches']."</h2>";
        }
    }
} else {
    $hit_result = '';
    $flip_link  = '';
    $section_content = '<h2>'.$lang['category']."</h2>\n".
                       '<p>'.$lang['status_idle']."</p>\n";
}

$contents =<<<EOD
{$hit_result}
<div class="section">
{$flip_link}{$section_content}{$flip_link}
</div>
EOD;

xhtml_output('file');
?>