<?php
/**
 * Create A New User Account
 *
 * $Id: admin/root/user_register.php, 2005/02/05 14:18:43 Exp $
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
 
if (isset($_SESSION['root_admin_login'], $_SESSION['root_user_name'], $_SESSION['root_user_pass'],
          $_POST['new_user'], $_POST['new_pass'], $_POST['new_email']) &&
          (!is_null($_POST['new_user'])) && (!is_null($_POST['new_pass'])) && (!is_null($_POST['new_email']))) {
    if (($_SESSION['root_user_name'] == $user) && ($_SESSION['root_user_pass'] == md5($password))) {
        
        // Matching a valid User name
        if (!preg_match('/^[0-9a-zA-Z]{2,16}$/i', $_POST['new_user'])) {
            $contents = '<h2 id="account-manager">'.$lang['invalid_name'].'</h2>'.
                        '<p><span class="stronger">&#187;</span>&#160;<a href="./user_list.php">'.
                        $lang['user_list'].'</a> | <a href="./user_regist.php">'.$lang['create_accounts'].'</a></p>'.
                        '<p class="warning">'.$lang['invalid_name_msg'].'</p>';
        // Matching a valid User password
        } elseif (!preg_match('/^[0-9a-zA-Z]{4,16}$/i', $_POST['new_pass'])) {
            $contents = '<h2 id="account-manager">'.$lang['invalid_pass'].'</h2>'.
                        '<p><span class="stronger">&#187;</span>&#160;<a href="./user_list.php">'.
                        $lang['user_list'].'</a> | <a href="./user_regist.php">'.$lang['create_accounts'].'</a></p>'.
                        '<p class="warning">'.$lang['invalid_pass_msg'].'</p>';
        // Matching a valid Email address
        } elseif (!preg_match('/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $_POST['new_email'])) {
            $contents = '<h2 id="account-manager">'.$lang['invalid_email'].'</h2>'.
                        '<p><span class="stronger">&#187;</span>&#160;<a href="./user_list.php">'.
                        $lang['user_list'].'</a> | <a href="./user_regist.php">'.$lang['create_accounts'].'</a></p>'.
                        '<p class="warning">'.$lang['invalid_email_msg'].'</p>';
        } else {
            if ($_SESSION['root_admin_login'] == '') {
                $_SESSION['root_user_name']   = $mysql_user;
                $_SESSION['root_user_pass']   = $mysql_pass;
                $_SESSION['root_admin_login'] = TRUE;
            }
            $new_user  = insert_safe($_POST['new_user']);
            $new_pass  = insert_safe($_POST['new_pass']);
            $new_email = insert_safe($_POST['new_email']);

            $sql = "INSERT INTO `{$user_table}` VALUES ('', '{$new_user}', md5('{$new_pass}'), '{$new_email}', CURRENT_TIMESTAMP())";
            if (!$result = mysql_query($sql)) die (mysql_error());
            $contents = '<h2 id="account-manager">'.$lang['new_user_created']."</h2>\n". 
                        '<p>'.$lang['new_user_created_success']."</p>\n".
                        '<p class="ref"><a href="./user_list.php">'.$lang['user_list']."</a></p>\n".
                        '<p class="ref"><a href="./user_regist.php">'.$lang['create_accounts']."</a></p>\n";
        
            // Send Mail
            if ($cfg['sendmail_account_id'] == 'yes') {
                if ($cfg['xml_lang'] == 'ja') {
                    require_once './mail_mb.php';
                } else {
                    require_once './mail.php';
                }
            }
        }
    } else {
        $contents = bad_req_error();
    }
} else {
    $contents =<<<EOD
<h2 id="account-manager">{$lang['create_accounts']}</h2>
<p><a href="./user_list.php">{$lang['user_list']}</a> | {$lang['create_accounts']}</p>
<form action="user_regist.php" method="post">
<table summary="Create User Account">
<thead>
<tr><th colspan="2">{$lang['new_user_account']}</th></tr>
</thead>
<tbody>
<tr><th>{$lang['user_name']} : </th><td><input type="text" name="new_user" class="bordered" /></td></tr>
<tr><th>{$lang['user_pass']} : </th><td><input type="password" name="new_pass" class="bordered" /></td></tr>
<tr><th>E-Mail : </th><td><input type="text" name="new_email" class="bordered" /></td></tr>
</tbody>
<tfoot>
<tr>
<td colspan="2">
<input type="submit" name="Submit" value="{$lang['create']}" />
</td>
</tr>
</tfoot>
</table>
</form>
EOD;

}

$admin = 'yes';

xhtml_output('');

?>