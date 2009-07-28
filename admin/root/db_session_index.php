<?php
/**
 * Session Manager Index
 *
 * $Id: admin/root/db_session_index.php, 2005/02/05 14:11:23 Exp $
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

if (isset($_SESSION['root_admin_login'], $_SESSION['root_user_name'], $_SESSION['root_user_pass'])) {
    if (($_SESSION['root_user_name'] == $user) && ($_SESSION['root_user_pass'] == md5($password))) {
        if ($_SESSION['root_admin_login'] == '') {
            $_SESSION['root_user_name']   = $mysql_user;
            $_SESSION['root_user_pass']   = $mysql_pass;
            $_SESSION['root_admin_login'] = TRUE;
        }
        if ($_SESSION['root_admin_login'] == 1) {
            $show_sess_sql = "SELECT * FROM `{$session_table}`";
            $show_sess_res = mysql_query($show_sess_sql);
            $contents = "<h2>SESSION MANAGER</h2>"."\n".
                        '<table class="colored">'."\n".
                        '<tr>'."\n".
                        '<th class="colored">ID</th>'."\n".
                        '<th class="colored">'.$lang['regist_date'].'</th>'."\n".
                        '<th class="colored">'.$lang['mod_del'].'</th></tr>'."\n";
        
            while ($row = mysql_fetch_array($show_sess_res)) {
                $contents .= '<tr>'."\n".
                             '<td class="colored-left-top">'.$row[0].'</td>'."\n".
                             '<td class="colored">'.strftime('%G/%m/%d %Z<br />%H:%M %p', $row[2]).'</td>'."\n".
                             '<td class="colored">'."\n".
                             '<form action="./db_session_delete.php" method="post">'."\n".
                             '<input type="hidden" name="sess_id" value="'.$row[0].'" />'."\n".
                             '<input type="submit" value="'.$lang['delete'].'" />'."\n".
                             '</form>'."\n".
                             '</td></tr>'."\n".
                             '<tr>'."\n".
                             '<td class="colored" colspan="3">'.str_replace(';', ';<br />', $row[1]).'</td>'."\n".
                             '</tr>'."\n";
            }
            $contents .= '</table><br />'."\n".
                         '<form action="./db_session_delete.php" method="post">'."\n".
                         '<input type="hidden" name="sess_gc" value="go" />'."\n".
                         '<input type="submit" value=" Force Garbage Collect " />'."\n".
                         '</form>'."\n";
        }
        
        $admin = 'yes';
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