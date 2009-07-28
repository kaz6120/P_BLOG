<?php
/**
 * Modify Forum Logs
 *
 * $Id: forum/admin/modify.php, 2005/01/13 16:58:17 Exp $
 */

$cd = '../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once '../include/fnc_search.inc.php';
require_once '../include/fnc_forum.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_POST['id'], $_POST['tid'])) {
        $id  = intval($_POST['id']);
        $tid = intval($_POST['tid']);
        // Main Contents
        $sql = 'SELECT'.
                " `id`, `tid`, `parent_key`, `title`, `comment`, `user_name`, `user_pass`, `user_mail`, `user_uri`, `user_ip`, `color`, DATE_FORMAT(`mod`, '%Y/%m/%d %T') as `mod`".
                " FROM `{$forum_table}`".
                " WHERE `id` = '{$id}'";
        $res  = mysql_query($sql) or die(mysql_error());
        $rows = mysql_num_rows($res);
        if ($rows) {
            while ($row = mysql_fetch_array($res)) {
                $row['id']        = sanitize(intval($row['id']));
                $row['tid']       = sanitize(intval($row['tid']));
                $row['user_name'] = sanitize($row['user_name']);
                $row['user_pass'] = sanitize($row['user_pass']);
                $row['user_mail'] = sanitize($row['user_mail']);
                $row['user_uri']  = sanitize($row['user_uri']);
                $row['user_ip']  = sanitize($row['user_ip']);
                $row['title']     = sanitize($row['title']);
                $row['color']     = sanitize(intval($row['color']));
                $row['comment']   = $row['comment'];
                $row = convert_to_utf8($row);
                $contents =<<<EOD

<ul class="flip-menu">
<li><a href="../index.php">{$lang['topic_list']}</a></li>
<li><a href="../topic.php?tid={$tid}&amp;p=0">{$lang['back_to_topic']}</a></li>
</ul>
EOD;
                // Init user info
                $user_ip   = '<p>IP : ' . $row['user_ip'] . '</p>';
                $user_mail = $row['user_mail'];
                $user_pass = $row['user_pass'];
            
                // Check user defined text color and...Switch!
                switch ($row['color']) {
                    case '3':
                        $check0 = '';
                        $check1 = '';
                        $check2 = '';
                        $check3 = ' checked="checked"';
                        break;
                    case '2':
                        $check0 = '';
                        $check1 = '';
                        $check2 = ' checked="checked"';
                        $check3 = '';
                        break;
                    case '1':
                        $check0 = '';
                        $check1 = ' checked="checked"';
                        $check2 = '';
                        $check3 = '';
                        break;
                    default:
                        $check0 = ' checked="checked"';
                        $check1 = '';
                        $check2 = '';
                        $check3 = '';
                        break;
                }
                // settings..
                $action    = './modified.php';
                $input_pass =<<<EOD
<input tabindex="7" accesskey="p" type="password" class="bordered" size="25" 
       onblur="if (value == '') {value = '{$lang['enter_a_new_pass']}'}" 
       onfocus="if (value == '{$lang['enter_a_new_pass']}') {value =''}" 
       type="text" value="{$lang['enter_a_new_pass']}" name="mod_user_pass" />
EOD;
                $parent_id = '<input type="hidden" name="tid" value="' . $tid . '" />'."\n";
            
                // Load presentation
                require_once '../contents/comment_edit.tpl.php';
            }
        }
    }
} else {
    $contents = wrong_user_error();
}

xhtml_output('forum');

?>