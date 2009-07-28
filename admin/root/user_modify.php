<?php
/**
 * Update User Account Info
 *
 * $Id: admin/root/user_modify.php, 2004/11/01 22:36:54 Exp $
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

if (isset($_SESSION['root_admin_login'], $_SESSION['root_user_name'], $_SESSION['root_user_pass'], $_POST['user_id'])) {
    if (($_SESSION['root_user_name'] == $user) && ($_SESSION['root_user_pass'] == md5($password))) {
        if ($_SESSION['root_admin_login'] == '') {
            $_SESSION['root_user_name']   = $mysql_user;
            $_SESSION['root_user_pass']   = $mysql_pass;
            $_SESSION['root_admin_login'] = TRUE;
        }
        
        $admin = 'yes';
        
        $ls_sql = "SELECT * FROM `".$user_table."` WHERE `user_id` = '" . $_POST['user_id'] . "'";
        $ls_res = mysql_query($ls_sql);
    
        $contents = "\n".
                    '<h2 id="account-manager">'.$lang['update_user_account']."</h2>\n".
                    '<p><a href="./user_list.php">'.$lang['user_list'].'</a> | <a href="./user_regist.php">'.$lang['create_accounts']."</a></p>\n".
                    '<form action="./user_modified.php" method="post">'."\n".
                    '<table class="colored">'."\n".
                    "<tr>\n".
                    '<th class="colored">ID</th><th class="colored">'.$lang['user_name'].' / '.$lang['user_pass']."</th>\n".
                    '<th class="colored">e-mail</th>'."\n".
                    '<th class="colored">'.$lang['update']."</th></tr>\n";
        while ($row = mysql_fetch_array($ls_res)) {
            $contents .= "<tr>\n".
                         '<th class="colored" rowspan="2">'.$row[0]."</th>\n".
                         '<td class="colored">'."\n".
                         '<input tabindex="1" accesskey="n" type="text" class="bordered" size="20" name="mod_user_name" value="'.$row[1].'" />'."\n".
                         "</td>\n".
                         '<td class="colored" rowspan="2">'."\n".
                         '<input tabindex="3" accesskey="e" type="text" class="bordered" name="mod_user_email" value="'.$row[3].'" />'."\n".
                         "</td>\n".
                         '<td  class="colored" rowspan="2">'."\n".
                         '<input type="hidden" name="user_id" value="'.$row[0].'" />'."\n".
                         '<input type="submit" value="&#160;'.$lang['save'].'&#160;" />'."\n".
                         "</td>\n</tr>\n".
                         '<td class="colored">'."\n".
                         '<input  tabindex="2" accesskey="p" type="password" class="bordered" size="25" onblur="'.
                         "if (value == '') {value = '".$lang['enter_a_new_pass']."'}\" onfocus=\"if (value == '".$lang['enter_a_new_pass']."') {value =''}\" ".
                         'type="text" value="'.$lang['enter_a_new_pass'].'" name="mod_user_pass" />'."\n".
                         "</td>\n";
        }
        $contents .= "</table>\n</form>\n<br />\n";

        xhtml_output('');

    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir. '/login.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir. '/login.php');
    exit;
}
?>