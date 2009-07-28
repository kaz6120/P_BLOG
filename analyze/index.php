<?php
/**
 * P_BLOG Access Analyzer
 *
 * $Id: analyze/index.php, 2005/02/12 01:47:44 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once 'include/fnc_analyzer.inc.php';
require_once 'include/bar_graph.php';


session_control();

against_xss();

if ($session_status == 'on'){

    // When year, month, date queries are posted...
    if (isset($_POST['y'], $_POST['m'], $_POST['d'])) {
        if (ereg("^[12][0-9]{3}$", $_POST['y']) &&
            ereg("^[01][0-9]{1}$", $_POST['m']) &&
            ereg("^[0123][0-9]{1}$", $_POST['d'])) {
            $selected_date = $_POST['y'].'-'.$_POST['m'].'-'.$_POST['d'];
            $date_result   = $selected_date;
        } else {
            $selected_date = '';
            $date_result   = 'TOTAL';
        }
        // Prepare SQLs
        //--------------------------------------------------------------------------------
        $count_sql  = 'SELECT `id` FROM ' . $analyze_table . " WHERE `date` LIKE '{$selected_date}%'";

        $sql_1 = 'SELECT COUNT(`id`) as `cnt`, HOUR(`date`) as `hour_row` '.
                 ' FROM ' . $analyze_table . " WHERE `date` LIKE '{$selected_date}%' ".
                 'GROUP BY `hour_row` ORDER BY `hour_row`';
    
        $sql_2 = 'SELECT COUNT(`id`) as `cnt`, `re_host`, `date`'.
                 ' FROM ' . $analyze_table . " WHERE `date` LIKE '{$selected_date}%' ".
                 ' GROUP BY `re_host`'.
                 ' HAVING COUNT(`id`) >= ' . $cfg['referer_limit_num'].
                 ' ORDER BY `cnt` DESC';

        $sql_3 = 'SELECT COUNT(`id`) as `cnt`, `browser`, `date`'.
                 ' FROM ' . $analyze_table . " WHERE `date` LIKE '{$selected_date}%' ".
                 ' GROUP BY `browser`'.
                 ' HAVING COUNT(`id`) >= ' . $cfg['referer_limit_num'].
                 ' ORDER BY `cnt` DESC';

        $sql_4 = 'SELECT COUNT(`id`) as `cnt`, `ref`, `date`'.
                 ' FROM ' . $analyze_table . " WHERE `date` LIKE '{$selected_date}%' ".
                 ' GROUP BY `ref`'.
                 ' HAVING COUNT(`id`) >= ' . $cfg['referer_limit_num'].
                 ' ORDER BY `cnt` DESC';

        // Count how many days have passed
        $days_sql  = 'SELECT TO_DAYS(`date`) as `td_row` FROM ' . $analyze_table . ' GROUP BY `td_row`';
        $days_res  = mysql_query($days_sql);
        $days_num  = mysql_num_rows($days_res);
        $limit_num = $days_num - 31;

        $sql_5 = 'SELECT COUNT(`id`) as `cnt`,'.
                 " DATE_FORMAT(`date`, '%Y/%m/%d, %W') as `dn_row`,".
                 ' TO_DAYS(`date`) as `td_row` FROM ' .$analyze_table.
                 ' GROUP BY `dn_row`, `td_row` ORDER BY `dn_row`';
                 if ($days_num >= 31) {
                     $sql_5 .= ' LIMIT ' . $limit_num . ', 31';
                 }
        $sql_6 = 'SELECT COUNT(`id`) as `cnt`,'.
                 " DATE_FORMAT(`date`, '%Y/%m') as `mn_row`,".
                 " DATE_FORMAT(`date`, '%Y/%m') as `month` FROM " .$analyze_table.
                 ' GROUP BY `mn_row`, `month` ORDER BY `month`';

        $sql_7 = 'SELECT COUNT(`id`) as `cnt`, YEAR(`date`) as `year`'.
                 ' FROM ' . $analyze_table .
                 ' GROUP BY `year` ORDER BY `year`';
            
        if ($cfg['use_download_counter'] == 'yes') {
            $sql_8 = 'SELECT `bin_count`, `binname`' .
                     ' FROM `' . $info_table . '`'.
                     ' ORDER BY `bin_count` DESC';
        }            
        // OK, put all these queries into MySQL query functions
        //--------------------------------------------------------------------------------
        // Count total hit of the day
        //--------------------------------------------------------------------------------
        $hitres = mysql_query($count_sql) or die("Boo. ".mysql_error());
        $hitrow = mysql_num_rows($hitres);
    
        // SQL : hits per hour
        //--------------------------------------------------------------------------------
        $res_1   = mysql_query($sql_1) or die("Boo. ".mysql_error());
        $array_1 = array();
        while ($row = mysql_fetch_row($res_1)) {
            $count = $row[0];
            $name  = $row[1];
            $array_1[$name] = $count;
        }
        $graph_1 = bar_graph_hour($array_1, 300, $cfg['xml_lang']);

        // SQL : remote host
        //--------------------------------------------------------------------------------
        $res_2   = mysql_query($sql_2) or die("Boo. ".mysql_error());
        $array_2 = array();
        while ($row = mysql_fetch_row($res_2)) {
            $count = $row[0];
            $name  = $row[1];
            $array_2[$name] = $count;
        }
        $sum = array_sum($array_2);
        if ($hitrow > $sum) {
            $array_2['...Others (less than ' .$cfg['referer_limit_num']. ' hits)'] = $hitrow - $sum;
        }
        $graph_2 = bar_graph($array_2, 300, $cfg['xml_lang']);
    
        // SQL : user agent
        //--------------------------------------------------------------------------------
        $res_3   = mysql_query($sql_3) or die("Boo. ".mysql_error());
        $array_3 = array();
        while ($row = mysql_fetch_row($res_3)) {
            $count = $row[0];
            $name  = $row[1];
            $array_3[$name] = $count;
        }
        $sum = array_sum($array_3);
        if ($hitrow > $sum) {
            $array_3['...Others (less than ' .$cfg['referer_limit_num']. ' hits)'] = $hitrow - $sum;
        }
        $graph_3 = bar_graph($array_3, 300, $cfg['xml_lang']);
    
        // SQL : referer
        //--------------------------------------------------------------------------------
        $res_4 = mysql_query($sql_4) or die("Boo. ".mysql_error());
        $array_4 = array();
        while ($row = mysql_fetch_row($res_4)) {
            $count = $row[0];
            $name  = $row[1];
            $array_4[$name] = $count;
        }
        $sum = array_sum($array_4);
        if ($hitrow > $sum) {
            $array_4['...Others (less than ' .$cfg['referer_limit_num']. ' hits)'] = $hitrow - $sum;
        }
        $graph_4 = bar_graph_with_link($array_4, 300, $cfg['xml_lang']);

        // SQL : Last 7 daily hits
        //--------------------------------------------------------------------------------
        $res_5   = mysql_query($sql_5) or die("Boo. ".mysql_error());
        $array_5 = array();
        while ($row = mysql_fetch_row($res_5)) {
            $count = $row[0];
            $name  = $row[1];
            $array_5[$name] = $count;
        }
        $graph_5 = bar_graph_week($array_5, 300, $cfg['xml_lang']);
                
        // SQL : monthly hits
        //--------------------------------------------------------------------------------
        $res_6   = mysql_query($sql_6) or die("Boo. ".mysql_error());
        $array_6 = array();
        while ($row = mysql_fetch_row($res_6)) {
            $count = $row[0];
            $name  = $row[1];
            $array_6[$name] = $count;
        }
        $graph_6 = bar_graph_total($array_6, 300, $cfg['xml_lang']);

        // SQL : yearly hits
        //--------------------------------------------------------------------------------
        $res_7   = mysql_query($sql_7) or die("Boo. ".mysql_error());
        $year_hitrow_7 = mysql_num_rows($res_7);
        $array_7 = array();
        while ($row = mysql_fetch_row($res_7)) {
            $count = $row[0];
            $name  = $row[1];
            $array_7[$name] = $count;
        }
        $graph_7 = bar_graph_total($array_7, 300, $cfg['xml_lang']);
            
        // SQL : downloads
        //--------------------------------------------------------------------------------
        if ($cfg['use_download_counter'] == 'yes') {
            $res_8   = mysql_query($sql_8) or die("Boo. ".mysql_error());
            $year_hitrow_8 = mysql_num_rows($res_8);
            $array_8 = array();
            while ($row = mysql_fetch_row($res_8)) {
                $count = $row[0];
                $name  = $row[1];
                $array_8[$name] = $count;
            }
            $graph_8 = bar_graph_total($array_8, 300, $cfg['xml_lang']);
        }
        // Show the result page

        $yyyy = $_POST['y'];
        $mm   = $_POST['m'];
        $dd   = $_POST['d'];
        
        $contents_body = display_body($hitrow, $date_result);
        
        // show the flush button
        if ($cfg['enable_del_logs'] == 'yes') {
            $contents_body .= display_comp_form();
            $contents_body .= display_del_form();
        }
    
    // Compress the old data    
    } elseif (isset($_POST['comp_year'], $_POST['comp_month']) && ($_POST['comp_year'] != '') && ($_POST['comp_month'] != '')) {

        $yyyy = date("Y");
        $dd   = date("m");
        $mm   = date("d");

        $comp_sql = 'UPDATE `'.$analyze_table.
                    "` SET `ref` = '', `browser` = '', `re_host` = '' WHERE `date` LIKE '".
                    $_POST['comp_year'].'-'.$_POST['comp_month']."%'";
        mysql_query($comp_sql) or die("Boo. ".mysql_error());
        // optimize table
        $optimize_sql = 'OPTIMIZE TABLE `'.$analyze_table.'`';
        mysql_query($optimize_sql) or die("Boo. ".mysql_error());
        if ($cfg['xml_lang'] == 'ja') {
            $contents_body = '<h3>'.$_POST['comp_year'].'-'.$_POST['comp_month']."のデータを圧縮しました。</h3>\n";
        } else {
            $contents_body = '<h3>Data on '.$_POST['comp_year'].'-'.$_POST['comp_month']." has been compressed.</h3>\n";
        }
    } elseif (isset($_POST['comp_year'], $_POST['comp_month']) && (($_POST['comp_year'] == '') || ($_POST['comp_month'] == ''))) {
        $yyyy = date("Y");
        $mm   = date("m");
        $dd   = date("d");
        $contents_body = '<h3>Ooops. '.$lang['bad_req']."</h3>\n";        
    } elseif (isset($_POST['del']) && ($_POST['del'] != '')) { // Request from "Delete" button with magic words
        $yyyy = date("Y");
        $mm   = date("m");
        $dd   = date("d");
        $post_del = md5($_POST['del']);
        if ($post_del == md5($cfg['del_magic_words'])) {
            $del_sql = 'TRUNCATE TABLE ' . $analyze_table;
            mysql_query($del_sql) or die("Boo. ".mysql_error());
            $contents_body = "<h3>{$lang['logs_deleted']}</h3>";
        } else {
            $contents_body = '<h3>Ooops. '.$lang['bad_req']."</h3>\n";
        }
    } elseif ((isset($_POST['del'])) && ($_POST['del'] == '')) { // Request from "Delete" button without magic words
        $yyyy = date("Y");
        $mm   = date("m");
        $dd   = date("d");
        $contents_body = '<h3>Ooops. '.$lang['bad_req']."</h3>\n";
    // Default
    } else {
        $yyyy = date("Y");
        $mm   = date("m");
        $dd   = date("d");
        $contents_body = display_login_success();
    }
    
    // Generate XHTML
    $analyzer  = display_analyze_header();
    $analyzer .= display_form($yyyy, $mm, $dd);
    $analyzer .= $contents_body;
    $analyzer .= display_analyzer_footer();
    echo $analyzer;
} else {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}

?>