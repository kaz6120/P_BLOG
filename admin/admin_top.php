<?php
/**
 * Logout from Admin Directory
 *
 * $Id: admin/go_to_admin.php, 2004/12/29 22:53:17 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

if ($session_status == 'on') {

    $contents =<<<EOD
<div class="section">
<h2>{$lang['system_admin']}</h2>
<ul class="flip-menu">
<li><span class="cur-tab">{$lang['sys_env']}</span></li>
<li><a href="./preferences.php">{$lang['preferences']}</a></li>
<li><a href="./edit_menu.php">{$lang['edit_custom_file']}</a></li>
<li><a href="./db_status.php">{$lang['db_table_status']}</a></li>
</ul>
EOD;
    if (mysql_select_db($dbname)) {
        $q  = 'SELECT ' . $cfg['count_row_query'] . ' FROM ' . $log_table;
        $countsql = ($q);
        $res = mysql_query($countsql);
        $row = mysql_num_rows($res);
	
        $q2 = 'SELECT ' . $cfg['count_row_query'] . ' FROM ' . $info_table;
        $countsql2 = ($q2);
        $res2 = mysql_query($countsql2);
        $row2 = mysql_num_rows($res2);
    
        if (PHP_VERSION >= 4.3) {
            $mysql_enc = mysql_client_encoding();
        } else {
            $mysql_enc = '';
        }
        $php_version    = PHP_VERSION;
        $mysql_version  = mysql_get_server_info();
        $p_blog_version = P_BLOG_VERSION;
        $php_os         = PHP_OS;
        
        if (extension_loaded('mbstring')) {
            $php_mutibyte_status = 'Loaded';
        } else {
            $php_mutibyte_status = 'Not loaded';
        }
        
        $contents .=<<<EOD

<table class="horizontal-graph" summary="System Enviroment Info">
<tr>
<th abbr="System Environment" colspan="2">{$cfg['blog_title']} {$lang['sys_env']}</th></tr>
<tr>
<td class="key">PHP version</td><td class="value"><strong>{$php_version}</strong></td></tr>
<tr>
<td class="key">PHP Multibyte Functions</td><td class="value"><strong>{$php_mutibyte_status}</strong></td></tr>
<tr>
<td class="key">MySQL</td><td class="value"><strong>{$mysql_version}</strong></td></tr>
<tr>
<td class="key">DB Language</td><td class="value"><strong>{$mysql_enc}</strong></td></tr>
<tr>
<td class="key">{$lang['db_name']}</td><td class="value"><strong>{$dbname}</strong></td></tr>
<tr>
<td class="key">{$lang['log_entry']}</td><td class="value"><strong>{$row}</strong></td></tr>
<tr>
<td class="key">{$lang['file_entry']}</td><td class="value"><strong>{$row2}</strong></td></tr>
<tr>
<td class="key">Server OS</td><td class="value"><strong>{$php_os}</strong></td></tr>
<tr>
<td class="key">Powered by</td><td class="value"><strong>P_BLOG ver.{$p_blog_version}</strong></td></tr>
<tr>
<th abbr="UI Info" colspan="2">{$lang['ui_info']}</th></tr>
<tr>
<td class="key">{$lang['css_cookie']}</td><td class="value"><strong>{$cfg['css_cookie_time']}</strong>&#160;sec</td></tr>
</table>
EOD;
    }
    $contents .=<<<EOD

<!-- Access Analyzer Button -->
<form method="post" action="../analyze/index.php">
<p class="submit-button">
<input type="hidden" value="" tabindex="10" accesskey="u" name="post_username" size="20" class="bordered" />
<input type="hidden" value="" tabindex="11" accesskey="p" name="post_password" size="20" class="bordered" />
<input type="submit" tabindex="12" accesskey="a" value="{$lang['analyze']}" />
</p>
</form>
</div>
EOD;
    if (file_exists('../var/rss_box/admin')) { $contents .= display_login_rss(); }
    
    xhtml_output('');

} else {

    if (($cfg['root_path'] == '/path/to/p_blog/') || (is_null($cfg['root_path']))) {
        $request_uri = $http . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        $uri = parse_url($request_uri);
        $uri = str_replace('admin_top.php', 'login.php', $uri);
        header('Location: ' . $http . '://' . $uri['host'] . $uri['path']);
        exit;
    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir . '/login.php');
        exit;
    }
    exit;
}
?>