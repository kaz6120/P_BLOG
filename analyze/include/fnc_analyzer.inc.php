<?php
/**
 * Functions of P_BLOG Access Analyzer
 *
 * $Id: functions.php, 2005/02/13 21:13:01 Exp $
 */


function display_analyze_header()
{
    global $cfg, $dtd, $page_title, $content_type, $cd, $style, $style_num, 
           $alternate_link_rss, $subtitle;
    
    xhtml_header();
    
    $analyzer_header =<<<EOD
{$dtd}
<head>
<title>{$page_title}</title>
{$content_type}<script type="text/javascript" src="{$cd}/include/scripts.js"></script>
<link rel="stylesheet" type="text/css" href="{$cd}/styles/{$style_num}/{$style}.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$cd}/styles/_media/print.css" media="print" />
<link rel="start" href="{$cd}/index.php" title="Home" />
<link rel="search" href="{$cd}/search_plus.php" title="Search" />
<link rel="help" href="{$cd}/var/help/index.php" title="Help" />
{$alternate_link_rss}<link rev="made" href="mailto:webmaster@anti-spam.example.com" />
<script type="text/javascript" src="{$cd}/include/block_spam.php?js_email_link"></script>
<meta name="robots" content="noindex,nofollow" />
</head>
<body>
<!-- Begin #wrapper -->
<div id="wrapper">
<!-- Begin #header -->
<div id="header">
<h1><a href="{$cd}/index.php" accesskey="t">{$cfg['blog_title']}</a></h1>
{$subtitle}</div>
<!-- End #header -->
EOD;
    return $analyzer_header;
}


/**
 * Date Selector Form
 */
function display_form($yyyy, $mm, $dd)
{
    global $cfg, $lang, $admin_dir;
    
    $year_list = '';
    for ($i = 2003; $i <= 2020; $i++) {
         $year_list .= '<option value="'.$i.'"'; 
         $year_list .= ($i == $yyyy) ? ' selected="selected"' : ''; 
         $year_list .= '>'.$i.'</option>'."\n"; 
    }
    
    $month_list = '';
    for ($i=1; $i<=12; $i++) {
        $val = sprintf("%02d",$i);
        $month_list .= '<option value="'.$val.'"';
        $month_list .= ($val == $mm) ? ' selected="selected"' : '';
        $month_list .=  '>'.$val.'</option>'."\n"; 
    }
    
    $date_list = '';
    for ($i=1; $i<=31; $i++) {
        $val = sprintf("%02d",$i);
        $date_list .= '<option value="'.$val.'"';
        $date_list .= ($val == $dd) ? ' selected="selected"' : "";
        $date_list .= '>'.$val.'</option>'."\n"; 
    }
    
    $analyzer_form =<<<EOD

<!-- Begin #wide-content -->
<div id="wide-content">
<ul class="flip-menu">
<li><a href="../{$admin_dir}/admin_top.php" class="title" accesskey="i">{$lang['admin']} Index</a></li>
<li><a href="../{$admin_dir}/login.php?status=logout" accesskey="l">{$lang['logout']}</a></li>
<li><span class="cur-tab">{$cfg['blog_title']} {$lang['analyze']}</span></li>
</ul>
<form method="post" action="./index.php">
<fieldset id="analyze-date">
<label for="y">{$lang['year']} : </label>
<select id="y" name="y" tabindex="1">
{$year_list}<option value="total">{$lang['total']}</option>
</select>
<label for="m">{$lang['month']} : </label>
<select id="m" name="m" tabindex="2">
{$month_list}<option value="total">{$lang['total']}</option>
</select>
<label for="d">{$lang['day']} : </label>
<select id="d" name="d" tabindex="3">
{$date_list}<option value="total">{$lang['total']}</option>
</select>
<input type="submit" tabindex="4" accesskey="g" value="GO" />
</fieldset>
</form>
EOD;
    return $analyzer_form;
}



/**
 * Show Log-in Succes Messages
 */
function display_login_success() {
    global $cfg, $lang;
    if ($cfg['xml_lang'] == 'en' || $cfg['xml_lang'] != 'ja') {
        $message = '<p>'.$lang['login_user'].' : '.$_SESSION['user_name'].'</p>'.
                   '<p>Choose Year, Month, Date and then click the "GO" button to Analyze.</p>';
    } elseif ($cfg['xml_lang'] == 'ja') {
        $message = '<p>'.$lang['login_user'].' : '.$_SESSION['user_name'].'</p>'.  
                   '<p>解析する年、月、日を選択し、「GO」ボタンをクリックして下さい。</p>';
    }
    return $message;
}


/**
 * Display Analyze Results
 */
function display_body($hitrow, $date_result) {
    global $cfg, $lang, 
           $graph_1, $graph_2, $graph_3, $graph_4, $graph_5, $graph_6, $graph_7, $graph_8;
    if ($cfg['use_download_counter'] == 'yes') {
        $download_counter = '<li><a href="#downloads">'.$lang['downloads'].'</a></li>';
        $download_counter_results =<<<EOD
<h3 id="downloads">{$lang['downloads']}</h3>
<table summary="Downloads" class="horizontal-graph">
<tr>
<th abbr="File">FILE</th><th abbr="Result" class="result">Results</th>
</tr>
{$graph_8}
EOD;
    } else {
        $download_counter = '';
        $download_counter_results = '';
    }
    $contents =<<<EOD
<h2>{$hitrow} Pageviews on {$date_result}</h2>
<ul>
<li><a href="#hits-per-hour">{$lang['hits_per_hour']}</a></li>
<li><a href="#remote-host">{$lang['remote_host']}</a></li>
<li><a href="#user-agent">{$lang['user_agent']}</a></li>
<li><a href="#referer">{$lang['referer']}</a></li>
<li><a href="#daily">{$lang['daily']}</a></li>
<li><a href="#monthly">{$lang['monthly']}</a></li>
<li><a href="#yearly">{$lang['yearly']}</a></li>
{$download_counter}
</ul>
<hr />
<h3 id="hits-per-hour">{$lang['hits_per_hour']}</h3>
<table summary="Hits per hour" class="horizontal-graph">
{$graph_1}
<h3 id="remote-host">{$lang['remote_host']}</h3>
<table summary="Remote Host" class="horizontal-graph">
<tr>
<th abbr="Hosts">Hosts</th><th abbr="Hits" class="hits">%</th><th abbr="Result" class="result">Results</th>
</tr>
{$graph_2}
<h3 id="user-agent">{$lang['user_agent']}</h3>
<table summary="User Agent" class="horizontal-graph">
<tr>
<th abbr="Browser&amp;OS">Browser &amp; OS Info</th><th abbr="Hits" class="hits">%</th><th abbr="Result" class="result">Results</th>
</tr>
{$graph_3}
<h3 id="referer">{$lang['referer']}</h3>
<table summary="Referer" class="horizontal-graph">
<tr>
<th abbr="Referer">Referer</th><th abbr="Hits" class="hits">%</th><th abbr="Result">Results</th>
</tr>
{$graph_4}
<h2>TOTALS</h2>
<h3 id="daily">{$lang['daily']}</h3>
<table summary="Total" class="horizontal-graph">
<tr>
<th abbr="Date">DATE</th><th abbr="Result" class="result">Results</th>
</tr>
{$graph_5}
<h3 id="monthly">{$lang['monthly']}</h3>
<table summary="Monthly hits" class="horizontal-graph">
<tr>
<th abbr="Month">MONTH</th><th abbr="Result" class="result">Results</th>
</tr>
{$graph_6}
<h3 id="yearly">{$lang['yearly']}</h3>
<table summary="Yearly hits" class="horizontal-graph">
<tr>
<th abbr="Year">YEAR</th><th abbr="Result" class="result">Results</th>
</tr>
{$graph_7}
{$download_counter_results}
EOD;
    
    return $contents;
}


/**
 * Error Message for Direct Access
 */
function display_body_default() {
    global $cfg, $lang;
    $error_message ==<<<EOD
<div id="wide-content">
<h2>Ooops. {$lang['wrong_user']}</h2>
</div>
</div>
</body>
</html>
EOD;
    return $error_message;
}



/**
 * Archive Compressor Form
 */
function display_comp_form()
{
    global $cfg, $lang;
    
    if ($cfg['xml_lang'] == 'ja') {
        $h2_title = 'ログデータ圧縮';
        $h3_title = '指定月のアクセスログデータを圧縮します。';
        $h3_msg   = 'データの圧縮をしたい年月を入力し、圧縮ボタンを押して下さい。';
        $h4_title = '※警告 : 使用上の注意';
        $h4_msg   = 'このボタンは、ブラウザ、IP、リファラー記録を空にし、データベーステーブルの最適化処理を行います。<br />圧縮した月は月別・年別のアクセス数のみ統計されるようになり、各日別のブラウザやIP、リファラーの個別記録表示は出来なくなります。';
        $month_to_optimize = '圧縮する年月';
        $compress = '圧縮!';
    } else {
        $h2_title = 'COMPRESS LOGS';
        $h3_title = 'COMPRESS PAST ACCESS LOGS';
        $h3_msg   = 'Enter year and month to optimize and click the button. ';
        $h4_title = 'WARNING : PLEASE USE THIS BUTTON WITH CARE.';
        $h4_msg   = 'This button empties the referer, user_agent, IP data and optimize the database table.<br />Please push this at your own risk.';
        $month_to_optimize = 'Month to optimize';
        $compress = 'Compress!';
    }    

    $compress_form =<<<EOD
<h2>{$h2_title}</h2>
<div class="section">
<h3>{$h3_title}</h3>
<p>{$h3_msg}</p>
<div class="important">
<h4>{$h4_title}</h4>
<p>{$h4_msg}</p>
</div>
<h4>{$month_to_optimize}</h4>
<form method="post" action="./index.php">
<p>{$lang['year']} : <input tabindex="5" accesskey="y" type="text" name="comp_year" size="4" value="" /> 
{$lang['month']} : <input tabindex="5" accesskey="m" type="text" name="comp_month" size="2" value="" />
<input type="submit" value="{$compress}" />
</p>
</form>
</div><!-- End .section -->
EOD;
    return $compress_form;
}


/**
 * Delete All Logs Form
 */
function display_del_form() {
    global $cfg, $lang;
    
    if ($cfg['xml_lang'] == 'ja') {
        $h2_title = 'ログ削除';
        $h3_title = '全てのアクセスログを削除します。';
        $h3_msg   = 'マジックワードを入力し、削除ボタンを押して下さい。';
        $h4_title = '※警告 : 使用上の注意';
        $h4_msg = 'このボタンは、テーブル内のデータを全て空にします。(空になるのはデータのみで、'.
                  'テーブルは削除されません。)<br />'.
                  'データベースを初期状態(空)に戻したい場合のみ、自己責任でご使用下さい。';
    } else {
        $h2_title = 'DELETE LOGS';
        $h3_title = 'DELETE ALL LOGS with magic words';
        $h3_msg   = 'Enter the magic words and click the button.';
        $h4_title = 'WARNING : PLEASE USE THIS BUTTON WITH CARE.';
        $H4_msg   = 'This button empties your log table. '.
                    'Yes, This button is very dangerous.<br />'.
                    'Please push this at your own risk.';
    }
    
    $delete_form =<<<EOD
<h2>{$h2_title}</h2>
<h3>{$h3_title}</h3>
<p>{$h3_msg}</p>
<div class="important">
<h4>{$h4_title}</h4>
<p>{$h4_msg}</p>
</div>
<form method="post" action="{$_SERVER['PHP_SELF']}">
<p>
{$lang['magic_words']}  : 
<input tabindex="6" accesskey="m" type="text" name="del" value="" />
</p>
<p>
<input tabindex="6" accesskey="d" type="submit" value="{$lang['del_all_logs']}" />
</p>
</form>
EOD;
    return $delete_form;
}


/**
 * P_ANAMA (Access Analyzer) Footer
 */
function display_analyzer_footer() {
    $mysql_version  = mysql_get_server_info();
    $php_os         = PHP_OS;
    $p_blog_version = P_BLOG_VERSION;
    $analyzer_footer =<<<EOD

</div>
<!-- Begin #footer -->
<div id="footer">
<address>
Powered by {$_SERVER['SERVER_SOFTWARE']} &amp; MySQL-{$mysql_version} running on {$php_os}<br />
Powered by P_BLOG ver.{$p_blog_version}
</address>
</div><!-- End #footer -->
</div><!-- End #wrapper -->
</body>
</html>
EOD;
    return $analyzer_footer;
}


// Deny access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
	die("Hello, World! This is an include file.");
}
?>
