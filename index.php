<?php
/*****************************************************************
 * P_BLOG - Simple & W3C-Valid Log System
 *
 * Copyright (c) 2003-2005 P_BLOG Project.
 * - <http://pbx.homeunix.org/p_blog/>
 *
 * Special Thanx:
 * - AYNiMac           <http://www.aynimac.com/>
 * - Hetima Computer   <http://hetima.com/>
 * - Gabriele Caniglia <http://www.caniglia.info>
 * - Sebastian Kupers  <http://sebastian.komaeleon.com/>
 * - Klaus Bach Media Design <http://klausbach.com/>
 *
 * $Id: index.php, 2005-11-29 11:17:43 Exp $
 *
 *****************************************************************/

$cd = '.';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';

session_control();

// Main Contents
$sort_sql = 'SELECT'.
            " `id`, `href`, `name`, `date`, DATE_FORMAT(`mod`, '%Y-%m-%d %T') as `mod`, `comment`, `category`, `draft`" .
            ' FROM ' . $log_table .
            ' WHERE `draft` = 0' .
            " ORDER BY `date` DESC LIMIT " . $cfg['pagemax'];
$res  = mysql_query($sort_sql);
if (!$res) {
    die(mysql_error());
} else {
    $rows = mysql_num_rows($res);
}
if ($rows) {
    $section_content = '';
    if ($cfg['show_date_title'] == 'yes') {
        $row = mysql_fetch_array($res);
        format_date($row_name = 'date');
        $title_date    = $formatted_date;
        $section_title = '<h2 class="date-title">' . $title_date . '</h2>';
        do {
            format_date($row_name = 'date');
            $tmp_date = $formatted_date;
            if ($title_date != $tmp_date) { 
                $title_date = $tmp_date;
                $section_content .= '</div><!-- End .section -->'."\n\n".
                                    '<div class="section">'."\n".
                                    '<h2 class="date-title">'.$title_date."</h2>\n";
            }
            $row = convert_to_utf8($row);
            $section_content .= display_article_box($row);
        } while ($row = mysql_fetch_array($res));
    } else {
        $section_title = '<h2>'.$lang['recent'].'<strong>'.$rows.'</strong>'.$lang['logs'].'</h2>';
        while ($row = mysql_fetch_array($res)) {
            $row = convert_to_utf8($row);
            $section_content .= display_article_box($row);
        }
    }
    $section_content .= display_prev_logs_navi('search');
} else {
    if (file_exists('./SETUP/')) {
        @include_once './SETUP/welcome.inc.php';
    } else {
        $section_title   = '<h2>Welcome to ' . $cfg['blog_title'] . ' !</h2>';
        $section_content = '<p>' . $lang['no_posts'] . '</p>';
    }
}

// Presentation
$contents =<<<EOD
<div class="section">
{$section_title}
{$section_content}
</div><!-- End .section -->
EOD;

xhtml_output('log');

// Access Analyzer
if (($session_status != 'on') && ($cfg['use_analyzer'] == 'yes')) {
    @include_once "./analyze/env_info_insert.php";
}
?>