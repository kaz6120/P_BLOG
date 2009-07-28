<?php
/**
 * Update User Account Info in MySQL
 *
 * $Id: admin/root/user_modified.php, 2004/10/29 14:28:00 Exp $
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

if (isset($_SESSION['root_admin_login'], $_SESSION['root_user_name'], $_SESSION['root_user_pass'], $_POST['mod_user_name'], $_POST['mod_user_pass'], $_POST['mod_user_email'], $_POST['user_id'])) {
    if (($_SESSION['root_user_name'] == $user) && ($_SESSION['root_user_pass'] == md5($password))) {
        // Matching a valid User name
        if (!preg_match('/^[0-9a-zA-Z]{2,16}$/i', $_POST['mod_user_name'])) {
            $contents = '<h2 id="account-manager">'.$lang['invalid_name'].'</h2>'.
                        '<p><span class="stronger">&#187;</span>&#160;<a href="./user_list.php">'.
                        $lang['user_list'].'</a> | <a href="./user_regist.php">'.$lang['create_accounts'].'</a></p>'.
                        '<p class="warning">'.$lang['invalid_name_msg'].'</p>';
        // Matching a valid User password
        } elseif (!preg_match('/^[0-9a-zA-Z]{4,16}$/i', $_POST['mod_user_pass'])) {
            $contents = '<h2 id="account-manager">'.$lang['invalid_pass'].'</h2>'.
                        '<p><span class="stronger">&#187;</span>&#160;<a href="./user_list.php">'.
                        $lang['user_list'].'</a> | <a href="./user_regist.php">'.$lang['create_accounts'].'</a></p>'.
                        '<p class="warning">'.$lang['invalid_pass_msg'].'</p>';
        // Matching a valid Email address
        } elseif (!preg_match('/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $_POST['mod_user_email'])) {
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
            $mod_user_name  = insert_safe($_POST['mod_user_name']);
            $mod_user_pass  = insert_safe($_POST['mod_user_pass']);
            $mod_user_email = insert_safe($_POST['mod_user_email']);
            $user_id        = insert_safe(intval($_POST['user_id']));

            $sql = "UPDATE `{$user_table}` SET `user_name` = '$mod_user_name', `user_pass` = md5('$mod_user_pass'), `user_mail` = '{$mod_user_email}' WHERE `user_id` = '{$user_id}'";
            if (!$result = mysql_query($sql)) die (mysql_error());
            $contents = '<h2 id="account-manager">'.$lang['account_updated'].'</h2>'.
                        '<h3>'.$lang['account_updated_msg'].'</h3>'.
                        '<p><span class="stronger">&#187;</span>&#160;<a href="./user_list.php">'.
                        $lang['user_list'].'</a> | <a href="./user_regist.php">'.$lang['create_accounts'].'</a></p>';
                        // Send Mail
            if ($cfg['sendmail_account_id'] == 'yes') {
                if ($cfg['xml_lang'] == 'ja') {
                    require_once './mail_mb.php';
                } else {
                    require_once './mail.php';
                }
            }
        }
        
        $admin = 'yes';
        xhtml_output('');
        
    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>