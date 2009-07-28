<?php
/**
 * Show User List
 *
 * $Id: admin/root/user_list.php, 2004/11/12 03:02:42 Exp $
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
            $ls_sql = "SELECT * FROM `{$user_table}` ORDER BY `user_id`";
            $ls_res = mysql_query($ls_sql);
                        
            $admin = 'yes';

            $contents =<<<EOD

<h2 id="account-manager">{$lang['user_list']}</h2>
<p>{$lang['user_list']} | <a href="./user_regist.php">{$lang['create_accounts']}</a></p>
<table summary="User List">
<thead>
<tr>
<th abbr="ID">ID</th>
<th abbr="Name">{$lang['user_name']} / {$lang['user_pass']} (MD5)</th>
<th abbr="Mail">e-mail</th>
<th abbr="Date">{$lang['regist_date']}</th>
<th abbr="Modify or Delete">{$lang['mod_del']}</th>
</tr>
</thead>
<tbody>
EOD;
                 
            while ($row = mysql_fetch_array($ls_res)) {
                $contents .=<<<EOD
<tr>
<th abbr="No" rowspan="2">{$row[0]}</th>
<td class="person">{$row[1]}</td>
<td rowspan="2">{$row[3]}</td>
<td rowspan="2">{$row[4]}</td>
<td>
<form action="./user_modify.php" method="post">
<div>
<input type="hidden" name="user_id" value="{$row[0]}" />
<input type="submit" value="{$lang['update']}" />
</div>
</form>
</td>
</tr>
<tr>
<td>{$row[2]}</td>
<td>
<form action="./user_delete.php" method="post">
<div>
<input type="hidden" name="user_id" value="{$row[0]}" />
<input type="submit" value="{$lang['delete']}" />
</div>
</form>
</td>
</tr>
EOD;

            }
            $contents .= "</tbody>\n</table>\n";
        }

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