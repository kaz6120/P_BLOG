<?php
/**
 * Root User (MySQL USer) Login
 *
 * $Id: admin/root/root_login.php, 2005/03/11 00:51:04 Exp $
 */

$cd = '../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once '../include/fnc_admin.inc.php';

if ($cfg['use_session_db'] == 'yes') {
    require_once '../db_session.php';
} else {
    session_name($cfg['p_blog_root_sess_name']);
    session_start();
}

// Initialize session variables
$_SESSION['root_admin_login'] = 0;
$_SESSION['root_user_name']   = 0;
$_SESSION['root_user_pass']   = 0;
$_SESSION['user_name']        = 0;

            
if (isset($_POST['mysql_user'], $_POST['mysql_pass'])) {
    $mysql_user = addslashes($_POST['mysql_user']);
    $mysql_pass = md5($_POST['mysql_pass']);
    if (($mysql_user == $user) && ($mysql_pass == md5($password))) {
        if ($_SESSION['root_admin_login'] == '') {
            $_SESSION['root_user_name']   = $mysql_user;
            $_SESSION['root_user_pass']   = $mysql_pass;
            $_SESSION['root_admin_login'] = TRUE;
        }
        if ($_SESSION['root_admin_login'] == 1) {
        
            $contents =<<<EOD

<h2 id="account-manager">{$lang['login_success']}</h2>
<p class="ref"><a href="./user_list.php">{$lang['user_list']}</a></p>
<p class="ref"><a href="./user_regist.php">{$lang['create_accounts']}</a></p>
EOD;
                 
            if ($cfg['use_session_db'] == 'yes') {
                $contents .=<<<EOD

<form method="post" action="db_session_index.php">
<fieldset> 
<legend accesskey="a">SESSION MANAGER</legend>
<input type="hidden" value="" tabindex="2" accesskey="u" name="post_username" size="20" class="bordered" />
<input type="hidden" value="" tabindex="3" accesskey="p" name="post_password" size="20" class="bordered" />
<p><input type="submit" tabindex="4" accesskey="l" value=" Go " /></p>
</fieldset>
</form>
EOD;
            }
        } else {
            $contents = 'OUT OF SESSION.';
        }
    } else {
        $contents = bad_req_error();
    }
} else {
    $contents =<<<EOD

<h2 id="account-manager">{$lang['account_manager']}</h2>
<h3 id="root-user-only">({$lang['root_user_only']})</h3>
<form action="root_login.php" method="post">
<fieldset>
<legend accesskey="m">{$lang['enter_mysql_account']}</legend>
<label for="mysql_user">MySQL {$lang['user_name']}&#160;:&#160;</label>
<input tabindex="1" accesskey="u" type="text" id="mysql_user" name="mysql_user" class="bordered" />
<br />
<label for="mysql_pass">MySQL {$lang['user_pass']}&#160;:&#160;</label>
<input tabindex="2" accesskey="p" type="password" id="mysql_pass" name="mysql_pass" class="bordered" />
<p>
<input tabindex="3" accesskey="l" type="submit" name="Submit" value="{$lang['login']}" />
</p>
</fieldset>
</form>
EOD;

}

$admin = 'yes';
xhtml_output('');

?>