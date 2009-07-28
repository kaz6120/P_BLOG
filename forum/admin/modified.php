<?php
/**
 * Update Logs
 *
 * $Id: forum/admin/modified.php, 2005/11/13 17:49:00 Exp $
 */

$cd = '../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once '../include/fnc_search.inc.php';
require_once '../include/fnc_forum.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_POST['user_name'], $_POST['title'], $_POST['comment'], $_POST['id'], $_POST['mod_user_pass'], $_POST['tid'], $_POST['mod_del'])) {
        $user_name     = insert_safe($_POST['user_name']);
        $mod_user_pass = insert_safe($_POST['mod_user_pass']);
        $title         = insert_tag_safe($_POST['title']);
        $comment       = insert_tag_safe($_POST['comment']);
        $id            = insert_safe(intval($_POST['id']));
        $tid           = insert_safe(intval($_POST['tid']));
        $mod_del       = insert_safe(intval($_POST['mod_del']));

        $contents =<<<EOD

<ul class="flip-menu">
<li><a href="../index.php">{$lang['topic_list']}</a></li>
<li><a href="../topic.php?tid={$tid}&amp;p=0">{$lang['back_to_topic']}</a></li>
</ul>
EOD;
        if ($cfg['enable_unicode'] == 'on') {
            mb_convert_variables($cfg['mysql_lang'], "auto", $user_name, $title, $comment);
        }
        switch($mod_del) {
            case '1':
                $sql = "DELETE FROM `{$forum_table}` WHERE `id` = '{$id}'";
                $res = mysql_query($sql);
                if ($res) {
                    $contents .= '<h2>'. $lang['comment']. ' ID:' . $id . $lang['deleted'] . '</h2>';
                }
                break;
            default:
                $sql = "UPDATE `{$forum_table}` SET `title` = '{$title}', `comment` = '{$comment}', `user_name` = '{$user_name}', `user_pass` = md5('{$mod_user_pass}') ".
                       "WHERE `id` = '{$id}'";
                $res = mysql_query($sql);
                if ($res) {
                     $contents .= '<h2>'.$lang['log'].'ID : '.$id. $lang['updated'].'</h2>';
                }
                break;
        }
    } else {
        $contents = wrong_user_error();
    }
} else {
    $contents = wrong_user_error();
}

xhtml_output('forum');

?>