<?php
/**
 * Search Plus+ (Advance search) Routine
 * 
 * $Id: search_plus.php, 2006-08-02 22:25:21 Exp $
 */

$cd = '.';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';
require_once $cd . '/include/search_plus.inc.php';
require_once $cd . '/include/fnc_flip_plus.inc.php';
require_once $cd . '/include/fnc_search.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';

session_control();

against_xss();

//=====================================================
// PREPARE SEARCH QUERY
//=====================================================
if (isset($_GET['f'], $_GET['k'], $_GET['ao'], $_GET['p'], $_GET['ds'], $_GET['d'], $_GET['d1'], $_GET['d2'], $_GET['c'], $_GET['pn'])) {
    // To check if keyword value is not too short
    if (($_GET['k'] != '') && (strlen($_GET['k']) <= 2 )) {
         $contents = keyword_error($mode = 'log', $error_type = '2');
         xhtml_output('log');
         exit;
    }
    $field = $_GET['f'];
    if ($cfg['enable_unicode'] == 'on') {
        $keyword = mb_convert_encoding(trim($_GET['k']), $cfg['mysql_lang'], "auto");
    } else {
        $keyword = trim($_GET['k']);
    }
    $ao    = $_GET['ao']; // and or
    $page  = $_GET['p'];  // page
    $date  = $_GET['d'];  // date
    $ds    = $_GET['ds']; // date select
    $d1    = $_GET['d1']; // date 1
    $d2    = $_GET['d2']; // date 2
    $case  = $_GET['c'];  // case-insensitive (c = 0) or sensitive ( c = 1)
    
    // Case sensitive flag
    // If case-sensitive, search target field as BINARY.
    if ($case == 1) {
        $binary_flag = 'BINARY ';
    } else {
        $binary_flag = '';
    }
            
    if ($field != '') {
        $sql = 'SELECT'
             . " `id`, `href`, `name`, "
             . "DATE_FORMAT(`date`, '%Y-%m-%d %T') as `date`, "
             . "DATE_FORMAT(`mod`,'%Y-%m-%d %T') as `mod`, "
             . "`comment`, `category`, `draft`"
             . " FROM `{$log_table}` WHERE (`draft` = '0') AND (";
        if (($field != '0') && ($keyword != '')) { // keyword search
            // Keywords
            if (!strrchr($keyword, " ")) {
                $keys = explode(",", $keyword);
                $and_or = $ao;
            } else {
                $keys = explode(" ", $keyword);
                $and_or = $ao;
            }
            
            $sql .= $binary_flag;
            switch ($field) {
                case '2':
                    $sql .= "`name`";
                    break;
                case '3':
                    $sql .= "`href`";
                    break;
                case '4':
                    $sql .= "`comment`";
                    break;
                default:
                    $sql .= "`name` LIKE '%".$keys[0]."%' OR ".$binary_flag."`comment`";
                    break;
            }
            $sql .= " LIKE '%".$keys[0]."%')";
            for ($i = 1; $i < sizeof($keys); $i++) {
                $sql .= $and_or. ' (' . $binary_flag;
                switch ($field) {
                    case '2':
                        $sql .= "`name`";
                        break;
                    case '3':
                        $sql .= "`href`";
                        break;
                    case '4':
                        $sql .= "`comment`";
                        break;
                    default:
                        $sql .= "`name` LIKE '%".$keys[$i]."%' OR ".$binary_flag."`comment`";
                        break;
                }
                $sql .= " LIKE '%".$keys[$i]."%')";
            }
            // Date
            switch ($ds) {
                case '0':
                    $sql .= '';
                    break;
                case '1':
                    $sql .= " AND (`date` LIKE '".$date."%')";
                    break;
                case '2':
                    $sql .= " AND (`date` BETWEEN '".$d1."' AND '".$d2."')";
                    break;
                default:
                    $sql .= " AND (`date` LIKE '".$date."%')";
                    break;
            }
            // Category
            if ((isset($_GET['cat'])) && ($_GET['cat'] != '')) {
                $cat   = array($_GET['cat']);
                if (is_array($cat[0])) {
                    $sql .= ' AND (';
                    $sql .= "`category` LIKE '%".$cat[0][0]."%'";
                    for ($j = 1; $j < sizeof($cat[0]); $j++) {
                        $sql .= " OR `category` LIKE '%".$cat[0][$j]."%'";
                    }
                    $sql .= ')';
                }
            }
        } elseif (($field == '0') && ($keyword == '')) { // monthly search
            $sql .= "`date` LIKE '".$date."%')";
        }
        $sql .= " ORDER BY `date` DESC LIMIT {$page}, {$cfg['pagemax']} ";
    } else {
        // Error
        $contents = no_keywords_error($mode = 'log');
        xhtml_output('log');
        exit;
    }  
 
    //=====================================================
    // SUBMIT SEARCH QUERY
    //=====================================================
    if ($res = mysql_query($sql)) {
    
        // Query results --- count all hit data
        if (!strrchr($keyword, " ")) {
            $keys = explode(",", $keyword);
            $and_or = $ao;
        } else {
            $keys = explode(" ", $keyword);
            $and_or = $ao;
        }
        $count_sql = "SELECT `id`, DATE_FORMAT(`date`, '%Y-%m-%d') FROM `{$log_table}` WHERE (`draft` = '0') AND (" . $binary_flag;
        switch ($field) {
            case '2':
                $count_sql .= "`name`";
                break;
            case '3':
                $count_sql .= "`href`";
                break;
            case '4':
                $count_sql .= "`comment`";
                break;
            default:
                $count_sql .= "`name` LIKE '%".$keys[0]."%' OR ".$binary_flag."`comment`";
                break;
        }
        $count_sql .= " LIKE '%".$keys[0]."%')";  
        for ($i = 1; $i < sizeof($keys); $i++) {
            $count_sql .= $and_or . ' (' . $binary_flag;
            switch ($field) {
                case '2':
                    $count_sql .= "`name`";
                    break;
                case '3':
                    $count_sql .= "`href`";
                    break;
                case '4':
                    $count_sql .= "`comment`";
                    break;
                default:
                    $count_sql .= "`name` LIKE '%".$keys[$i]."%' OR ".$binary_flang."`comment`";
                    break;
            }
            $count_sql .= " LIKE '%".$keys[$i]."%')";
        }
        // Date
        switch ($ds) {
            case '0':
                $count_sql .= '';
                break;
            case '1':
                $count_sql .= " AND (`date` LIKE '".$date."%')";
                break;
            case '2':
                $count_sql .= " AND (`date` BETWEEN '".$d1."' AND '".$d2."')";
                break;
            default:
                $count_sql .= " AND (`date` LIKE '".$date."%')";
                break;
        }
        // Category
        if ((isset($_GET['cat'])) && ($_GET['cat'] != '')) {
            $cat   = array($_GET['cat']);
            if (is_array($cat[0])) {
                $count_sql .= ' AND (';
                $count_sql .= "`category` LIKE '%".$cat[0][0]."%'";
                for ($j = 1; $j < sizeof($cat[0]); $j++) {
                    $count_sql .= " OR `category` LIKE '%".$cat[0][$j]."%'";
                }
                $count_sql .= ')';
            }
        }
        $hit_res = mysql_query($count_sql);
        $hit_row = mysql_num_rows($hit_res);
    
        // Show the hit data info.
        $rows    = mysql_num_rows($res);
        $keyword = utf8_convert($keyword);

        if ($keyword == '') {
            if ($date != '') {
                $archive_title = $date;
            } else {
                $archive_title = $lang['all_data'];
            }
            $result_msg = $lang['show_log'];
        } elseif ($keyword != '') {
            $archive_title = $lang['keyword'].' : '.htmlspecialchars(stripslashes($keyword));
            $result_msg = $lang['hit_msg'];
            switch ($field) {
                case '2':
                    $hit_field = $lang['title_hit'];
                    break;
                case '3':
                    $hit_field = $lang['uri_hit'];
                    break;
                case '4':
                    $hit_field = $lang['comment_hit'];
                    break;
                default:
                    $hit_field = $lang['title_comment_hit'];
                    break;
            }
            switch ($ds) {
                case '0':
                    $hit_by_month = '';
                    break;
                case '1':
                    $hit_by_month = ' '.$lang['by_month'].'鐚<span class="search-res">'.$date."</span>\n";
                    break;
                case '2':
                    $hit_by_month = "</p>\n<p>".$lang['by_month'].'鐚<span class="search-res">'.$d1.'</span> - <span class="search-res">'.$d2."</span>";
                    break;
                default:
                    $hit_by_month = '';
                    break;
            }
        }
        // Presentation of the result
        $disp_page  = $page + 1;
        $disp_rows  = $page + $rows;
        $hit_result =<<<EOD
<div class="setcion">
<h2 id="archive-title">{$archive_title}</h2>
<p class="search-res">{$hit_field}
<span class="search-res">{$hit_row}</span>{$result_msg} 
<span class="search-res">{$disp_page} - {$disp_rows}</span> / <span class="search-res">{$hit_row}</span>{$hit_by_month}
</p>
</div><!-- End .section -->
EOD;
        
        //=================================================
        // SHOW THE RESULTS!
        //=================================================
        if ($hit_row) {
            $flip_link = display_page_flip_plus();
            //------------- WITH-DATE-TITLE MODE --------------
            if ($cfg['show_date_title'] == 'yes') {
                $row = mysql_fetch_array($res);
                format_date($row_name = 'date');
                $title_date = $formatted_date;
                $section_content = '<h2 class="date-title">'.$title_date."</h2>\n";
                do {
                    format_date($row_name = 'date');
                    $tmp_date = $formatted_date;
                    if ($title_date != $tmp_date) {
                        $new_title_date = $tmp_date;
                        $section_content .= '</div><!-- End .section -->'."\n\n".
                                            '<div class="section">'."\n".
                                            '<h2 class="date-title">'.$new_title_date."</h2>\n";
                    }
                    $row = highlight_keywords('log');
                    $row = convert_to_utf8($row);
                    $section_content .= display_article_box($row);
                } while ($row = mysql_fetch_array($res));
            //------------- WITHOUT-DATE-TITLE MODE --------------
            } else {
                $section_content = '';
                while ($row = mysql_fetch_array($res)) {
                    $row = highlight_keywords($row, 'log');
                    $row = convert_to_utf8($row);
                    $section_content .= display_article_box($row);
                }
            }
        } else {
            $flip_link  = '';
            $section_content = '<h3>'.$lang['no_matches']."</h3>\n";
            $section_content .= display_search_plus();
        }
    } else {
        $hit_result = '';
        $flip_link  = '';
        $section_content = no_keywords_error($mode = 'log');
    }
} else {
    // Show the default interface
    $hit_result = '';
    $flip_link  = '';
    $section_content  = '<h2 id="archive-title">SEARCH PLUS+</h2>'."\n";
    $section_content .= display_search_plus();
}

$contents =<<<EOD
{$hit_result}
<div class="section">
{$flip_link}{$section_content}{$flip_link}
</div>
EOD;

xhtml_output('log');

?>
