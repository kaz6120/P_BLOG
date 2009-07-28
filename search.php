<?php
/**
 * Search Routine
 * 
 * $Id: search.php, 2005/07/16 23:31:47 Exp $
 */

$cd = '.';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';
require_once $cd . '/include/search_plus.inc.php';
require_once $cd . '/include/fnc_search.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';

session_control();

against_xss();

//=====================================================
// PREPARE SEARCH QUERY
//=====================================================

 if ((empty($_GET['k'])) && (empty($_GET['p'])) && (empty($_GET['pn'])) && (empty($_GET['c']))) {
    $_GET['k']  = '';
    $_GET['p']  = '0';
    $_GET['pn'] = '1';
    $_GET['c']  = '0';
}

if (isset($_GET['k'], $_GET['p'], $_GET['d'], $_GET['c'], $_GET['pn'])) {
    // To check if keyword value is not too short
    if (($_GET['k'] != '') && (strlen($_GET['k']) <= 2 )) {
         $contents = keyword_error($mode = 'log', $error_type = '2');
         xhtml_output('log');
         exit;
    }
    if ($cfg['enable_unicode'] == 'on') {
        $keyword = mb_convert_encoding(trim($_GET['k']), $cfg['mysql_lang'], "auto");
    } else {
        $keyword = trim($_GET['k']);
    }
    
    $date  = $_GET['d'];
    $page  = $_GET['p'];
    $case  = $_GET['c'];
    
    // Case sensitive flag
    // If case-sensitive, search target field as BINARY.
    if ($case == 1) { // case-sensitive
        $binary_flag = 'BINARY ';
    } else {
        $binary_flag = '';
    }
    
    if (($page != '') & ($case != '')) {
        if (($keyword == '') && ($date == '')) {
            // Redirect to "search plus"...
            header('Location: '.$http.'://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].'search_plus.php');
            exit;
        } else {
            $sql = 'SELECT'.
                   " `id`, `href`, `name`, `date`, DATE_FORMAT(`mod`, '%Y-%m-%d %T') as `mod`, ".
                   "`comment`, `category`, `draft`".
                   " FROM `{$log_table}`";
            if ($date != "all") { 
                if ($session_status == 'on') { 
                    $search_draft = '(`draft` = 0 OR 1) AND '; 
                } else { 
                    $search_draft = '(`draft` = 0) AND'; 
                }
                $sql .= ' WHERE '.$search_draft.' ('; 
            } else { 
                if ($session_status == 'on') { 
                    $search_draft = '(`draft` = 0 OR 1) '; 
                } else { 
                    $search_draft = '(`draft` = 0) '; 
                }
                $sql .= ' WHERE ' . $search_draft; 
            }
        }
        if ($keyword != '') { // keyword search
            if (!strrchr($keyword, " ")) {
                $keys = explode(",", $keyword);
                $and_or = 'OR';
            } else {
                $keys = explode(" ", $keyword);
                $and_or = 'AND';
            }
            $sql .= $binary_flag . "`name` LIKE '%".$keys[0]."%' OR ";
            $sql .= $binary_flag . "`comment` LIKE '%".$keys[0]."%')";
            for ($i = 1; $i < sizeof($keys); $i++) {
                $sql .= $and_or. ' (';
                $sql .= $binary_flag . "`name` LIKE '%".$keys[$i]."%' OR ";
                $sql .= $binary_flag . "`comment` LIKE '%".$keys[$i]."%')";
            }
			if ($date != "all") {
                $sql .= " AND (`date` LIKE '".$date."%')";
			}
        } elseif ($keyword == '') { // monthly search
			if ($date != "all") {
                $sql .= "`date` LIKE '".$date."%')";
			}
        }
        $sql .= ' ORDER BY date ';
        if ($cfg['date_order_desc'] == 'yes') {
            $sql.= 'DESC';
        }
        $sql .= ' LIMIT ' . $page . ', ' . $cfg['pagemax'];
    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] .$cfg['root_path']. 'search_plus.php');
        exit;
    }  
 
    //=====================================================
    // SUBMIT SEARCH QUERY
    //=====================================================
    if ($res = mysql_query($sql)) {
    
        // Query results --- count all hit data
        if (!strrchr($keyword, " ")) {
            $keys = explode(",", $keyword);
            $and_or = 'OR';
        } else {
            $keys = explode(" ", $keyword);
            $and_or = 'AND';
        }
        //$count_sql  = "SELECT `id` FROM `{$log_table}` WHERE (`draft` = '0') AND (";
        if ($session_status == 'on') {
            $search_draft_num = '(`draft` = 0 OR 1) AND ';
        } else { 
            $search_draft_num = '(`draft` = 0) AND ';
        }
        $count_sql = "SELECT `id` FROM `{$log_table}` WHERE ".$search_draft_num.'('; 
        $count_sql .= $binary_flag . "`name` LIKE '%".$keys[0]."%' OR ";
        $count_sql .= $binary_flag . "`comment` LIKE '%".$keys[0]."%')";
        for ($i = 1; $i < sizeof($keys); $i++) {
            $count_sql .= $and_or . ' (';
            $count_sql .= $binary_flag . "`name` LIKE '%".$keys[$i]."%' OR ";
            $count_sql .= $binary_flag . "`comment` LIKE '%".$keys[$i]."%')";
        }
		if ($date != "all") {
            $count_sql .= " AND (`date` LIKE '".$date."%')";
		}
        $hit_res = mysql_query($count_sql);
        $hit_row = mysql_num_rows($hit_res);
    
        ///////////////////////////////////////////////////////////////////////////////////
        // Switch the search result messages and titles.
        $rows = mysql_num_rows($res);
        $keyword = utf8_convert($keyword);
        // If "date" query string matches "yyyy-mm-dd", return empty value.
        if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}/', $date)) {
            $contents   = '';
            $hit_result = '';
        } else {
            if ((($keyword == '') && (preg_match('/^[0-9]{4}-[0-9]{2}/', $date))) ||
                (($keyword == '') && ($date == 'all'))) {
                if (preg_match('/^[0-9]{4}-[0-9]{2}/', $date)) {
                    $yyyy  = substr($date, 0, 4);
                    $mm    = substr($date, 5, 2);
                    $date_array = getdate(mktime(0, 0, 0, $mm, 1, $yyyy));
                    $month = $date_array['month'];
                    $mday  = $date_array['mday'];
                    $year  = $date_array['year'];
                    switch($cfg['date_style']) {
                        case '1':
                            $df = 'Y/m';
                            break;
                        case '2':
                            $df = 'F Y';
                            break;
                        default:
                            $df = 'Y-m';
                            break;
                    }
                    $archive_title = date($df, strtotime($mday . ' ' . $month . ' ' . $year));
                } elseif ($date == 'all') {
                    $archive_title = $lang['all_data'];
                }
                $result_msg = ' ' . $lang['show_log'];
            } elseif (($keyword != '')) {
                $archive_title = $lang['keyword'].' : '.htmlspecialchars(stripslashes($keyword));
                if ($date != '') {
                    $archive_title .= ' '.$lang['by_month'].': <span class="search-res">'.$date.'</span>';
                }
                $result_msg = ' ' . $lang['hit_msg'];
            }
            // Pesentation of the results
            $disp_page = $page + 1;
            $disp_rows = $page + $rows;
            $hit_result =<<<EOD
<div class="section">
<h2 id="archive-title">{$archive_title}</h2>
<p class="search-res">
<span class="search-res">{$hit_row}</span>{$result_msg} 
<span class="search-res">{$disp_page} - {$disp_rows}</span> / <span class="search-res">{$hit_row}</span>
</p>
</div><!-- End .section -->
EOD;
        }    
        //=================================================
        // SHOW THE RESULTS!
        //=================================================
        if ($hit_row) {
            $flip_link = display_page_flip();
            //------------- WITH-DATE-TITLE MODE --------------
            if ($cfg['show_date_title'] == 'yes') {
                $row = mysql_fetch_array($res);
                format_date($row_name = 'date');
                $title_date = $formatted_date;
                $section_content = '<h2 class="date-title">' . $title_date . '</h2>';
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
                    $row = highlight_keywords('log');
                    $row = convert_to_utf8($row);
                    $section_content .= display_article_box($row);
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
    $section_content = '<h2>'.$lang['search']."</h2>\n".
                       '<p>'.$lang['status_idle']."</p>\n";
}


$contents =<<<EOD
{$hit_result}
<div class="section">
{$flip_link}{$section_content}{$flip_link}
</div>
EOD;

xhtml_output('log');

?>
