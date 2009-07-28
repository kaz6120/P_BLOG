<?php
/**
 * P_BLOG INSTALL WIZARD STEP-1
 *
 * $Id: 2006/02/04 15:11:50 Exp $
 */

// Redirect the direct accesses to this file
if (stristr($_SERVER['PHP_SELF'], '.inc.php')) {
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). '/index.php');
}

// Load constants definitions
require_once './include/constants.inc.php';

// Switch the language include file
if ((isset($_REQUEST['ex-lang'])) && ($_REQUEST['ex-lang'] == 'ja')) {
    require_once './lang/japanese.inc.php';
    $ex_lang = 'ja';
} else {
    require_once './lang/english.inc.php';
    $ex_lang = 'en';
}

/////////////////////////////////// BEGIN OF PERMISSION CHECK ////////////////////////////////////////////////

// Check "resources" dierectory permission
if (is_dir('../resources')) {
    if ((is_readable('../resources/')) && (is_writable('../resources/'))) {
        $perm_msg = $lang['perm_looks_ok'];
        $perm_icon = STATUS_GREEN;
    } else {
        $perm_msg = '<span class="important">'.$lang['perm_looks_bad'].'</span>';
        $perm_icon = STATUS_RED;
    }
} else {
    $perms = '';
    $perm_msg = '→ <span class="important">' . $lang['no_user_inc'] . '</span>';
    $perm_icon = STATUS_RED;
}


// Check "user_include" directory permission
if (is_dir('../include/user_include')) {
    if ((is_readable('../include/user_include/')) && (is_writable('../include/user_include/'))) {
        $perm_msg2 = $lang['perm_looks_ok'];
        $perm_icon2 = STATUS_GREEN;
    } else {
        $perm_msg2 = '<span class="important">'.$lang['perm_looks_bad'].'</span>';
        $perm_icon2 = STATUS_RED;
    }
} else {
    $perms2 = '';
    $perm_msg2 = '→ <span class="important">' . $lang['no_resoures'] . '</span>';
    $perm_icon2 = STATUS_RED;
}

// Check "user_include/menu.inc.php" permission
if (file_exists('../include/user_include/menu.inc.php')) {
    if ((is_readable('../include/user_include/menu.inc.php')) && (is_writable('../include/user_include/menu.inc.php'))) {
        $perm_msg3 = $lang['perm_looks_ok'];
        $perm_icon3 = STATUS_GREEN;
    } else {
        $perm_msg3 = '<span class="important">'.$lang['perm_looks_bad'].'</span>';
        $perm_icon3 = STATUS_RED;
    }
} else {
    $perms3 = '';
    $perm_msg3 = '→ <span class="important">' . $lang['no_menu'] . '</span>';
    $perm_icon3 = STATUS_RED;
}

// Check "user_include/css_rss.inc.php" permission
if (file_exists('../include/user_include/css_rss.inc.php')) {
    if ((is_readable('../include/user_include/css_rss.inc.php')) && (is_writable('../include/user_include/css_rss.inc.php'))) {
        $perm_msg4 = $lang['perm_looks_ok'];
        $perm_icon4 = STATUS_GREEN;
    } else {
        $perm_msg4 = '<span class="important">'.$lang['perm_looks_bad'].'</span>';
        $perm_icon4 = STATUS_RED;
    }
} else {
    $perms4 = '';
    $perm_msg4 = '→ <span class="important">' . $lang['no_css_rss'] . '</span>';
    $perm_icon4 = STATUS_RED;
}

// Check "user_include/base_xhtml.inc.php" permission
if (file_exists('../include/user_include/base_xhtml.inc.php')) {
    if ((is_readable('../include/user_include/base_xhtml.inc.php')) && (is_writable('../include/user_include/base_xhtml.inc.php'))) {
        $perm_msg5 = $lang['perm_looks_ok'];
        $perm_icon5 = STATUS_GREEN;
    } else {
        $perm_msg5 = '<span class="important">'.$lang['perm_looks_bad'].'</span>';
        $perm_icon5 = STATUS_RED;
    }
} else {
    $perms5 = '';
    $perm_msg5 = '→ <span class="important">' . $lang['no_base_xhtml'] . '</span>';
    $perm_icon5 = STATUS_RED;
}

// Check "user_config.inc.php" permission
if (file_exists('../include/user_config.inc.php')) {
    if ((is_readable('../include/user_config.inc.php')) && (is_writable('../include/user_config.inc.php'))) {
        $perm_msg6 = $lang['perm_looks_ok'];
        $perm_icon6 = STATUS_GREEN;
    } else {
        $perm_msg6 = '<span class="important">'.$lang['perm_looks_bad'].'</span>';
        $perm_icon6 = STATUS_RED;
    }
} else {
    $perms6 = '';
    $perm_msg6 = '→ <span class="important">' . $lang['no_user_conf'] . '</span>';
    $perm_icon6 = STATUS_RED;
}


/////////////////////////////////// END OF PERMISSION CHECK ////////////////////////////////////////////////

/////////////////////////////////// BEGIN OF ENVIRONMENT CHECK /////////////////////////////////////////////

// PHP version check
$php_version = PHP_VERSION;
if ($php_version >= '4.2') {
    $php_status_icon = STATUS_GREEN;
} elseif (($php_version < '4.2') && ($php_version >= '4.2')) {
    $php_status_icon = STATUS_YELLOW;
} elseif ($php_version < '4.2') {
    $php_status_icon = STATUS_RED;
} else {
    $php_status_icon = STATUS_RED;
}


// Modify "user_config.inc.php"
if (isset($_POST['bad_dbname'],   $_POST['rewrite_dbname'],
          $_POST['bad_host'],     $_POST['rewrite_host'],
          $_POST['bad_user'],     $_POST['rewrite_user'],
          $_POST['bad_password'], $_POST['rewrite_password'])) {
    
    // Clean up posted variables.
    $_POST['bad_dbname']       = strip_tags(trim($_POST['bad_dbname']));
    $_POST['rewrite_dbname']   = strip_tags(trim($_POST['rewrite_dbname']));
    $_POST['bad_host']         = strip_tags(trim($_POST['bad_host']));
    $_POST['rewrite_host']     = strip_tags(trim($_POST['rewrite_host']));
    $_POST['bad_user']         = strip_tags(trim($_POST['bad_user']));
    $_POST['rewrite_user']     = strip_tags(trim($_POST['rewrite_user']));
    $_POST['bad_password']     = strip_tags(trim($_POST['bad_password']));
    $_POST['rewrite_password'] = strip_tags(trim($_POST['rewrite_password']));
    
    // If empty value is posted, replace it to default value and save.
    if (empty($_POST['rewrite_dbname'])) {
        $_POST['rewrite_dbname'] = 'p_blog';
    }
    if (empty($_POST['rewrite_host'])) {
        $_POST['rewrite_host'] = 'localhost';
    }
    if (empty($_POST['rewrite_user'])) {
        $_POST['rewrite_user'] = 'mysql-user';
    }
    if (empty($_POST['rewrite_password'])) {
        $_POST['rewrite_password'] = 'mysql-pass';
    }

    $f = fopen('../include/user_config.inc.php', 'rb+') or die($php_errormsg);
    $s = fread($f, filesize('../include/user_config.inc.php'));

    $s = str_replace("'".$_POST['bad_dbname']  ."';", "'".$_POST['rewrite_dbname']  ."';", $s);
    $s = str_replace("'".$_POST['bad_host']    ."';", "'".$_POST['rewrite_host']    ."';", $s);
    $s = str_replace("'".$_POST['bad_user']    ."';", "'".$_POST['rewrite_user']    ."';", $s);
    $s = str_replace("'".$_POST['bad_password']."';", "'".$_POST['rewrite_password']."';", $s);

    rewind($f);
    if (-1 == fwrite($f, $s)) { die($php_errormsg); }
    ftruncate($f, ftell($f)) or die($php_errormsg);
    fclose($f) or die($php_errormsg);
}


// Rename the admin direcory and rewrite the user-config file.
if (isset($_POST['admin_dir'])) {
    $f = fopen('../include/user_config.inc.php', 'rb+') or die($php_errormsg);
    $s = fread($f, filesize('../include/user_config.inc.php'));
    $s = str_replace('admin-dir', $_POST['admin_dir'], $s);
    rewind($f);
    if (-1 == fwrite($f, $s)) { die($php_errormsg); }
    ftruncate($f, ftell($f)) or die($php_errormsg);
    fclose($f) or die($php_errormsg);
    if (file_exists('../admin-dir')) {
        rename('../admin-dir', '../' . $_POST['admin_dir']);
    }
}


// MySQL version check
require_once '../include/user_config.inc.php';

// Check all user define variables, and if there are some missings, return "STATUS_RED".
// If all variables are set properly, then return "STATUS_GREEN".
$dbname_check        = (empty($dbname))          ? '<span class="important">'.$lang['set_dbname']    .'</span><br />' : '';
$log_table_check     = (empty($log_table))       ? '<span class="important">'.$lang['set_log_table'] .'</span><br />' : '';
$info_table_check    = (empty($info_table))      ? '<span class="important">'.$lang['set_info_table'].'</span><br />' : '';
$data_table_check    = (empty($data_table))      ? '<span class="important">'.$lang['set_data_table'].'</span><br />' : '';
$analyze_table_check = (empty($analyze_table))   ? '<span class="important">'.$lang['set_ana_table'] .'</span><br />' : '';
$user_table_check    = (empty($user_table))      ? '<span class="important">'.$lang['set_user_table'].'</span><br />' : '';
$config_table_check  = (empty($config_table))    ? '<span class="important">'.$lang['set_conf_table'].'</span><br />' : '';
$tb_table_check      = (empty($trackback_table)) ? '<span class="important">'.$lang['set_tb_table']  .'</span><br />' : '';

$host_check          = (empty($host))            ? '<span class="important">'.$lang['set_host_name'] .'</span><br />' : '';
$user_check          = (empty($user))            ? '<span class="important">'.$lang['set_user_name'] .'</span><br />' : '';
$user_check          = (empty($user))            ? '<span class="important">'.$lang['set_user_pass'] .'</span><br />' : '';

$admin_dir_check     = (empty($admin_dir))       ? '<span class="important">'.$lang['set_admin_dir'] .'</span><br />' : '';


// Syncronize the admin directory name and user-defined admin variables value.
if (($admin_dir != 'admin-dir') && (file_exists('../admin-dir'))) {
    rename('../admin-dir', '../' . $admin_dir);
}

// If All variables are set, then connect to MySQL and check its version.
if ((empty($dbname_check)) &&
    (empty($log_table_check)) &&
    (empty($info_table_check)) &&
    (empty($data_table_check)) &&
    (empty($analyze_table_check)) &&
    (empty($user_table_check)) &&
    (empty($config_table_check)) &&
    (empty($tb_table_check)) &&
    (empty($host_check)) &&
    (empty($user_check)) &&
    (empty($password_check)) &&
    (empty($admin_dir_check))) {
    $link = @mysql_connect($host, $user, $password);
    if ($link) {
        $mysql_version = mysql_get_server_info();
        if ($mysql_version >= '3.23') {
            $mysql_ver_check = 'ok';
        } elseif ($mysql_version < '3.23') {
            $mysql_ver_check = 'bad';
        } else {
            $mysql_ver_check = 'bad';
        }
        if ($mysql_ver_check == 'ok') {
            $mysql_status_icon = STATUS_GREEN;
        } else {
            $mysql_status_icon = STATUS_RED;
        }
        mysql_close($link);
    } else { // If connection error occurs, return "STATUS_RED" and show error messages.
        $dbname_check      = '<span class="ok">'.$lang['rewrite_dbname'].'</span><br />'.
                             '<div class="extra-comment">'.$lang['rewrite_dbname_msg'].'</div>'.
                             '<input type="hidden" name="bad_dbname" value="'.$dbname.'" />'.
                             '<input type="text" name="rewrite_dbname" value="'.$dbname.'" /><br />';
        $db_connect_check  = '<br /><span class="important">** '.$lang['connect_error'].' **</span><br />'.
                             '<div class="extra-comment">'.$lang['connect_error_msg'].'</div>';
        $host_check        = '<span class="important">'.$lang['rewrite_host'].'</span><br />'.
                             '<input type="hidden" name="bad_host" value="'.$host.'" />'.
                             '<input type="text" name="rewrite_host" value="'.$host.'" /><br />';
        $user_check        = '<span class="important">'.$lang['rewrite_user'].'</span><br />'.
                             '<input type="hidden" name="bad_user" value="'.$user.'" />'.
                             '<input type="text" name="rewrite_user" value="'.$user.'" /><br />';
        $password_check    = '<span class="important">'.$lang['rewrite_pass'].'</span><br />'.
                             '<input type="hidden" name="bad_password" value="'.$password.'" />'.
                             '<input type="text" name="rewrite_password" value="'.$password.'" /><br />';
        $mysql_status_icon = STATUS_RED;
    }
} else { // When with BAD settings...
    $mysql_status_icon = STATUS_RED;
}


/////////////////////////////////// END OF ENVIRONMENT CHECK /////////////////////////////////////////////


// if all check lists are OK, then enable the form button
if (($mysql_status_icon == STATUS_GREEN) && 
    ($perm_icon         == STATUS_GREEN) && 
    ($perm_icon2        == STATUS_GREEN) &&
    ($perm_icon3        == STATUS_GREEN) &&
    ($perm_icon4        == STATUS_GREEN)) {
    
    $disabled = '';
    // disable the modify button
    $form_begin = '';
    $form_end   = '';
} else {
    $disabled = 'disabled="disabled"';
    // enable the modify button only when host, user, pass, and admin-directory is unset.
    if ((!empty($host_check)) ||
        (!empty($user_check)) ||
        (!empty($password_check)) ||
        (!empty($admin_dir_check))) {
        $form_begin = '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
        $form_end   = '<input type="hidden" name="ex-lang" value="'.$_REQUEST['ex-lang'].'" />'.
                      '<div class="submit-button"><input type="submit" value="'.$lang['save_settings'].'" /></div>'.
                      '</form>';
    } else {
        $form_begin = '';
        $form_end   = '';
    }
}

/////////////////////////////////// PRESENTATION //////////////////////////////////////////
echo <<<EOD
<!-- Begin of status -->
<div id="announce">
<h2>{$lang['title']}</h2>
<div id="switch-lang">({$lang['anchor']}</a>)</div>
<img src="../images/p_blog_icon.png" class="p_blog-icon" width="128" height="128" alt="P_BLOG Icon" />
<p>{$lang['define_p_blog']}</p>
</div>
<h2><img src="./contents/resources/step1.png" width="37" height="37" alt="STEP-1" /> STEP 1</h2>

<h3>{$lang['check_perms']}</h3>
<table class="colored">
<tr>
<th>{$lang['target']}</th>
<th>{$lang['permissions']}</th>
<th>{$lang['status']}</th>
</tr>
<tr>
<td>/resources</td><td>{$perm_msg}</td><td>{$perm_icon}</td>
</tr>
<tr>
<td>/include/user_include</td><td>{$perm_msg2}</td><td>{$perm_icon2}</td>
</tr>
<tr>
<td>/include/user_include/menu.inc.php</td><td>{$perm_msg3}</td><td>{$perm_icon3}</td>
</tr>
<tr>
<td>/include/user_include/css_rss.inc.php</td><td>{$perm_msg4}</td><td>{$perm_icon4}</td>
</tr>
<tr>
<td>/include/user_include/base_xhtml.inc.php</td><td>{$perm_msg5}</td><td>{$perm_icon5}</td>
</tr>
<tr>
<td>/include/user_config.inc.php</td><td>{$perm_msg6}</td><td>{$perm_icon6}</td>
</tr>
</table>

<h3>{$lang['check_env']}</h3>
<table class="colored">
<tr>
<th>{$lang['software']}</th><th>{$lang['requirements']}</th><th>{$lang['your_env']}</th><th>{$lang['status']}</th></tr>
<tr>
<td>
<a href="http://www.php.net">
<img src="../images/php-power-micro.png" class="mini-logo" width="80" height="15" alt="PHP" />
</a> PHP
</td>
<td>4.2 〜</td>
<td>{$php_version}</td>
<td>{$php_status_icon}</td>
</tr>
<tr>
<td>
<a href="http://www.mysql.com">
<img src="../images/mysql_logo.png" class="mini-logo" width="80" height="15" alt="MySQL" />
</a> MySQL
</td>
<td>3.23.5x 〜</td>
<td>
{$form_begin}
{$dbname_check}
{$db_connect_check}
{$log_table_check}
{$info_table_check}
{$data_table_check}
{$analyze_table_check}
{$user_table_check}
{$config_table_check}
{$tb_table_check}
{$host_check}
{$user_check}
{$password_check}
{$admin_dir_check}
{$mysql_version}
{$form_end}
</td>
<td>{$mysql_status_icon}</td>
</tr>
</table>

<form action="./index.php?id=step2" method="post">
<p class="submit-button">
<input type="hidden" name="ex-lang" value="{$ex_lang}" />
<input type="submit" value=" {$lang['next']} &#187; " {$disabled} />
</p>
</form>
<!-- End of status -->
EOD;
?>
