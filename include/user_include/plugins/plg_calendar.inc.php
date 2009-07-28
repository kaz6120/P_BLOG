<?php
/**
 * Calendar Plug-in for P_BLOG
 * ------------------------------------------------------------------------------------------------
 * [ HOW TO USE ]
 *
 *   (1) Place this file in "/include/user_include/plugins".  
 *   (2) Open "Custom file edit" menu in preferences of P_BLOG and put the variable:
 *          
 *        {$plugin['PBPROJ']['calendar']}
 *       
 *       on wherever you want to place the calendar.
 * ------------------------------------------------------------------------------------------------
 * [ 使い方 ]
 *
 *   (1) このファイルを "/include/user_include/plugins" に入れます。
 *   (2) P_BLOGの管理モードの「カスタムファイル編集」を開き、カレンダー表示コード
 *      
 *       {$plugin['PBPROJ']['calendar']}
 *
 *       をテンプレート内の好きな場所に記述・配置してください。この変数コードを埋め込んだ場所にカレンダーが表示されます。
 */
 
/**
 * @package : Calendar Plug-in for P_BLOG
 * @author  : P_BLOG Project (mod by hetima)
 * @version : $Id: 2006/02/24 21:30:00 Exp $
 */
class P_BLOG_Calendar 
{
    var $_month_array=NULL;

    /*
     * @brief : Check archives init
     * @author: hetima
     */
    function init_month_array($date, $mode)
    {
        global $log_table, $info_table, $forum_table;
        if ($mode == 'file') {
            $target_table  = $info_table;
            $target_field1 = 'bindate';
            $target_field2 = 'draft';
        } elseif ($mode == 'forum') {
            $target_table  = $forum_table;
            $target_field1 = 'date';
            $target_field2 = 'trash';
        } else {
            $target_table  = $log_table;
            $target_field1 = 'date';
            $target_field2 = 'draft';
        }

        $sql = "SELECT DAYOFMONTH(`{$target_field1}`) as day FROM `{$target_table}` WHERE (`{$target_field1}` LIKE '{$date}%') AND (`{$target_field2}` = 0) GROUP BY day";
        $result=array();
        if ($res = @mysql_query($sql)) {
           while ($day = @mysql_fetch_row($res)) {
    	       $result[$day[0]]=TRUE;
           }
        }
	    $this->_month_array=$result;
    }

    /*
     * @brief : Check archives
     * @author: hetima
     */
    function log_exists($date, $mode)
    {
        if (is_array($this->_month_array) && isset($this->_month_array[$date])) {
            return TRUE;
        }
        return FALSE;
    }

    /*
     * @brief : Display calendar
     * @author: P_BLOG Project
     */
    function output($mode)
    {
        global $cfg, $cd;
        
        // Enable simple queries
        if ((empty($_GET['k'])) && (empty($_GET['p'])) && (empty($_GET['pn'])) && (empty($_GET['c']))) {
            $_GET['k']  = '';
            $_GET['p']  = '0';
            $_GET['pn'] = '1';
            $_GET['c']  = '0';
        }

        // If the date query is sent, regard it as a key value.
        // if not, use the current date as a key.
        if (!empty($_GET['d'])) {   
            $date_str = $_GET['d'];
            if (preg_match('/^[0-9]{4}-[0-9]{2}/', $date_str)) {
                $yyyy  = substr($date_str, 0, 4);
                $mm    = substr($date_str, 5, 2);
                $key_day = getdate(mktime(0, 0, 0, $mm, 1, $yyyy));
            } else {
                $key_day = getdate();
            }
        } else {
            $key_day = getdate();
        }

        // Variable $key_day is an array, so pull out the list data
        // and split it into the separated variables.
        $mon  = $key_day['mon'];
        $mday = $key_day['mday'];
        $year = $key_day['year'];
        
        // init log list
        $target_month = sprintf("%4d-%02d", $year, $mon);
        $this->init_month_array($target_month, $mode);

        // For Navigation
        $prev_month      = date('Y-m', mktime(0, 0, 0, $mon,     0, $year));
        $next_month      = date('Y-m', mktime(0, 0, 0, $mon + 1, 1, $year));
        $prev_month_link = date('M',   mktime(0, 0, 0, $mon,     0, $year));
        $next_month_link = date('M',   mktime(0, 0, 0, $mon + 1, 1, $year));

        // Title date format
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
        $year_and_month = date($df, strtotime($mday . ' ' . $key_day['month'] . ' ' . $year));


        // Days of the week
        if ($cfg['xml_lang'] == 'ja') {
            $day_of_the_week = array('日', '月', '火', '水', '木', '金', '土');
        } else {
            $day_of_the_week = array('S', 'M', 'T', 'W', 'T', 'F', 'S');
        }

        // Initialize the Calendar body
        $calendar_body = '';

        // Days of the previous month
        $first_day = getdate(mktime(0, 0, 0, $mon, 1, $year));
        $wday = $first_day['wday'];
        for ($i = 0; $i < $wday; $i++) {
            $calendar_body .= '<td class="day-of-prev-month">*</td>'."\n"; 
        }

        // Change directory
        if (($mode == 'file') || ($mode == 'forum')) {
            $cal_dir = '.';
        } elseif ($mode == 'admin') {
            $cal_dir = '../..';     
        } else {
            $cal_dir = $cd;
        }

        // Start making calendar
        $day = 1;
        while (checkdate($mon, $day, $year)) {

    	    if ($this->log_exists($day, $mode)){
    	        $target_date = sprintf("%4d-%02d-%02d", $year, $mon, $day);
                $uri = $cal_dir . '/search.php?d=' . $target_date;
    	        $date_str = '<td class="log-exists"><a href="'.$uri.'">'.$day.'</a></td>';
        	} else {
    	    	$date_str = '<td>'.$day."</td>\n";
    	    }
	        switch ($wday) {
    	        case '0': // When Sundays(0th day), add start tags ("<tr>") to start table rows.
                    if ($day == 1) {
                        $calendar_body .= $date_str;
                    } else {
                        $calendar_body .= "<tr>\n" . $date_str;
                    }
    	            break;
    	        case '6': // When Saturdays(6th day), add "</tr>" elements to break table rows.
    	            $calendar_body .= $date_str . "</tr>\n";
    	            break;
    	        default:
    	            $calendar_body .= $date_str;
	                break;
        	}
            $day++;
            $wday++;
            $wday = $wday % 7;
        }

        // Days of the next month
        if ($wday > 0) {
            while ($wday < 7) {
                $calendar_body .= '<td class="day-of-next-month">*</td>'."\n";
                $wday++;
            }
            $calendar_body .= "</tr>\n";
        } else {
            $calendar_body .= '';
        }

        //=====================================================
        // PRESENTATION
        //=====================================================
        $plugin['PBPROJ']['calendar'] =<<<EOD
<table id="calendar" summary="Archive calendar of this weblog">
<thead>
<tr>
<th colspan="7" abbr="Year and Month">{$year_and_month}</th>
</tr>
<tr>
<th abbr="Sun" class="sunday">{$day_of_the_week[0]}</th>
<th abbr="Mon">{$day_of_the_week[1]}</th>
<th abbr="Tue">{$day_of_the_week[2]}</th>
<th abbr="Wed">{$day_of_the_week[3]}</th>
<th abbr="Thu">{$day_of_the_week[4]}</th>
<th abbr="Fri">{$day_of_the_week[5]}</th>
<th abbr="Sat">{$day_of_the_week[6]}</th>
</tr>
</thead>
<tfoot>
<tr>
<td colspan="7">
<a href="{$cal_dir}/search.php?d={$prev_month}" class="prev-month">{$prev_month_link}</a> | 
<a href="{$cal_dir}/search.php?d={$next_month}" class="next-month">{$next_month_link}</a>
</td>
</tr>
</tfoot>
<tbody>
<tr>
{$calendar_body}</tbody>
</table>
EOD;
        return $plugin['PBPROJ']['calendar'];
    }
}

$calendar = new P_BLOG_Calendar;
$plugin['PBPROJ']['calendar'] = $calendar->output($mode);


// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
	header("HTTP/1.1 301 Moved Permanently");
	header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . "/index.php");
}
?>