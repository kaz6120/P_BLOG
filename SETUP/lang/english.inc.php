<?php
/**
 * LOCALIZABLE STRINGS - English
 *
 * $Id: lang/english.inc.php, 2005/01/21 22:22:19 Exp $
 */

if (stristr($_SERVER['PHP_SELF'], ".inc.php")){
	die("good-bye, world! :-P");
}
$lang['manual']         = 'Manual';
$lang['anchor']         = '<a href="' . $_SERVER['PHP_SELF'] . '?ex-lang=ja"> 日本語 ';
$lang['title']          = 'Welcome to P_BLOG ';
$lang['define_p_blog']  = 'P_BLOG is a PHP and MySQL driven Weblog sytem '.
                          'with binary file uploader, access analyzer, '.
                          'comment/forum system, Trackback, Ping sending, and extensible yet simple content management function "Vars".</p>'.
                          '<p>P_BLOG is an open sourced <abbr title="&quot;Free software&quot; is a matter of liberty, not price.">Free Software</abbr>'.
                          ' and is being developed under <abbr title="GNU Public License">GPL</abbr>, '.
                          'and generates <abbr title="World Wide Web Consortium">W3C</abbr>-Valid XHTML1.0 Strict or XHTML1.1.</p>';
$lang['license']        = 'License';
$lang['check_env']      = 'Environment Check';
$lang['software']       = 'Software';
$lang['requirements']   = 'Require';
$lang['your_env']       = 'Your Environment';
$lang['permissions']    = 'Permission Check';
$lang['status']         = 'Status';
$lang['os_independent'] = 'OS Independent.';
$lang['check_perms']    = 'Permission Check';
$lang['target']         = 'Target';
$lang['next']           = 'Next';

$lang['set_dbname']     = 'Please edit "user_config.inc.php" to define your database name.';
$lang['set_log_table']  = 'Please edit "user_config.inc.php" to define your log table name.';
$lang['set_info_table'] = 'Please edit "user_config.inc.php" to define your file info table name.';
$lang['set_data_table'] = 'Please edit "user_config.inc.php" to define your file data table name.';
$lang['set_ana_table']  = 'Please edit "user_config.inc.php" to define your access analyzer table name.';
$lang['set_user_table'] = 'Please edit "user_config.inc.php" to define your user table name.';
$lang['set_conf_table'] = 'Please edit "user_config.inc.php" to define your config table name.';
$lang['set_tb_table']   = 'Please edit "user_config.inc.php" to define yuor trackback table name.';

$lang['set_host_name']  = 'Please enter your MySQL host server name.';
$lang['set_user_name']  = 'Please enter your MySQL user name.';
$lang['set_user_pass']  = 'Please enter your MySQL user password.';
$lang['set_admin_dir']  = 'Please rename youor Admin directory.';
$lang['admin_dir_exp']  = 'Admin mode does not work with the "admin-dir" directory name. '.
                          'To hide your backstage path for secure, please edit this to the name of your choice. ';
$lang['rewrite_dbname'] = 'Set your database name';
$lang['rewrite_dbname_msg'] = 'Use default name if you are unsure or don\'t need to change it.';
$lang['connect_error']  = 'Not Connected to MySQL Now.';
$lang['connect_error_msg'] = 'You must set all three in below correctly to connect to MySQL.';
$lang['rewrite_host']   = 'Please check your MySQL host name again.';
$lang['rewrite_user']   = 'Please check your MySQL user name again.';
$lang['rewrite_pass']   = 'Please check your MySQL user pass again.';
$lang['perm_looks_ok']  = 'You can read and write -- OK.';
$lang['perm_looks_bad'] = 'Please change the permission to make it readable and writable.';
$lang['no_resoures']    = ' No "resources" directory found.';
$lang['no_user_inc']    = ' No "user_include" directory found.';
$lang['no_menu']        = 'No "menu.inc.php" file found.';
$lang['no_css_rss']     = 'No "css_rss.inc.php" file found.';
$lang['no_base_xhtml']  = 'No "base_xhtml.inc.php" file found.';
$lang['no_user_conf']   = ' No "user_config.inc.php" file found.';

$lang['save_settings']  = 'Save settings';
$lang['settings']       = 'Settings';
$lang['root_path_settings'] = 'Define your ROOT PATH';
$lang['root_path_ex']   = 'Define your top-level directory of your P_BLOG. No "http://yourdomain.com/" is required to enter. <br />Do NOT forget "/ " (slashes) at both ends.';
$lang['choose_default_lang'] = 'Choose Your Language';
$lang['choose_time_zone']    = 'Choose Your Timezone';
$lang['install_or_upgrade'] = 'INSTALL or UPGRADE';
$lang['install_ex']     = 'Choose INSTALL or UPGRADE and click the start button.';
$lang['install']        = 'INSTALL';
$lang['upgrade']        = 'UPGRADE';
$lang['start']          = 'Start!';
$lang['back_to_step2']  = 'Please Go Back to STEP-2';
$lang['step']           = 'Step';
$lang['results']        = 'Results';
$lang['create_db']      = 'Create DB';
$lang['select_db']      = 'Select DB';
$lang['create_table']   = 'Create Table';
$lang['create_field']   = '：Create Field：';
$lang['installed_defaults'] = 'Installed Default Values to ';
$lang['finished']       = ' Finished';
$lang['install_fin_msg'] =<<<EOD
<p>OK, you are almost finishing. To post your entries, you have to create a new admin user account ID and password first. 
Please create your ID and Password as follows.</p>
<ol>
<li>Click the link below to move to "Account Manager" to create admin user.<br />
<div class="command"><p class="ref"><a href="../{$admin_dir}/root/root_login.php">../{$admin_dir}/root/root_login.php</a></p></div></li>
<li>Enter your MySQL user name and MySQL password to login to Account Manager.</li>
<li>Log in with the Admin account and password you have created. Here is a &quot;Path&quot; to your admin mode.<br />
<div class="command"><p class="ref"><a href="../{$admin_dir}/login.php">../{$admin_dir}/login.php</a></p></div></li>
<li>If you could log in successfully, you would post, edit, and delete with this new Admin account and password. You can not use your MySQL user and MySQL pass to post or edit.</li>
</ol>
<div class="important">
<p>NOTE : Please DELETE this &quot;SETUP&quot; directory after setup.</p>
</div>
<p>Have fun with P_BLOG !<br />
Feel free to feedback  your comments or bug reports.</p>
EOD;
?>