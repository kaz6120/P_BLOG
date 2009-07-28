<?php
/**
 * Modify Comment
 *
 * $Id: forum/modify.php, 2004/12/20 10:37:25 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once './include/fnc_search.inc.php';
require_once './include/fnc_forum.inc.php';

against_xss();

if (isset($_REQUEST['id'], $_REQUEST['tid'])) {
    $id        = intval($_REQUEST['id']);
    $tid       = intval($_REQUEST['tid']);
    // Main Contents
    $sql = 'SELECT'.
           " `id`, `tid`, `parent_key`, `title`, `comment`, `user_name`, `user_pass`, `user_mail`, `user_uri`, `color`,".
           " DATE_FORMAT(`mod`, '%Y/%m/%d %T') as `mod`".
           ' FROM `'.$forum_table.'`'.
           " WHERE (`id` = '" . $id . "')";
    $res  = mysql_query($sql);
    if (!$res) {
        die(mysql_error());
    } else {
        $rows = mysql_num_rows($res);
    }
    if ($rows) {
        while ($row = mysql_fetch_array($res)) {
            $row = convert_to_utf8($row);
            $row['id']        = sanitize(intval($row['id']));
            $row['tid']       = sanitize(intval($row['tid']));
            $row['user_name'] = sanitize($row['user_name']);
            $row['user_pass'] = sanitize($row['user_pass']);
            $row['user_mail'] = sanitize($row['user_mail']);
            $row['user_uri']  = sanitize($row['user_uri']);
            $row['title']     = sanitize($row['title']);
            $row['color']     = sanitize(intval($row['color']));
            $row['comment']   = sanitize($row['comment']);
            $contents =<<<EOD

<ul class="flip-menu">
<li><a href="./index.php">{$lang['topic_list']}</a></li>
<li><a href="./add.php">{$lang['new_topic']}</a></li>
<li><a href="./topic.php?tid={$tid}">{$lang['back_to_topic']}</a></li>
</ul>
EOD;
            // Init user info
            $user_ip   = '';
            $user_mail = '';
            $user_pass = ''; //$row['user_pass'];
            
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
            $action     = './modified.php';
            $input_pass = '<input accesskey="p" tabindex="9" type="password" id="user_pass" name="user_pass" value="' . $user_pass .'" class="bordered" />';
            $parent_id  = '<input type="hidden" name="tid" value="' . $tid . '" />'."\n";
            
            // Load presentation
            require_once './contents/comment_edit.tpl.php';

        }
    } else {
        $contents = bad_req_error();
    }
} else {
    $contents = bad_req_error();
}

xhtml_output('forum');
?>
    