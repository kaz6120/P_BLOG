<?php
/**
 * Update Comment
 *
 * $Id: modified.php, 2005/11/13 17:48:17 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_search.inc.php';
require_once './include/fnc_forum.inc.php';

if (isset($_POST['user_name'], $_POST['user_pass'],  $_POST['title'],
          $_POST['comment'], $_POST['color'], $_POST['id'], $_POST['tid'])) {

    $user_name = insert_safe($_POST['user_name']);
    $user_pass = insert_safe(md5($_POST['user_pass']));
    $title     = insert_tag_safe($_POST['title']);
    $comment   = insert_tag_safe($_POST['comment']);
    $color     = insert_safe(intval($_POST['color']));
    $id        = insert_safe(intval($_POST['id']));
    $tid       = insert_safe(intval($_POST['tid']));
    $mod_del   = insert_safe($_POST['mod_del']);
    
    $contents =<<<EOD
<ul class="flip-menu">
<a href="./index.php" accesskey="i">{$lang['topic_list']}</a> 
<a href="./add.php" accesskey="n">{$lang['new_topic']}</a> 
<a href="./topic.php?tid={$tid}" accesskey="b">{$lang['back_to_topic']}</a>
</ul>
EOD;

    $check_sql = 'SELECT `user_pass` FROM `' . $forum_table . '` WHERE id = ' . $id;
    $check_res = mysql_query($check_sql);
    $check_row = mysql_fetch_array($check_res);
    if ($check_row['user_pass'] == $user_pass) {
        if ($cfg['enable_unicode'] == 'on') {
            mb_convert_variables($cfg['mysql_lang'], "auto", $user_name, $title, $comment);
        }
        
        if (isset($_POST['user_uri'])) {
            $user_uri = $_POST['user_uri'];
        }
        // Format current mod time
        $cmod  = gmdate('YmdHis', time() + ($cfg['tz'] * 3600));
        
        switch($mod_del) {
            case '1': // Delete
                $sql = "UPDATE `{$forum_table}` SET `trash` = '1' WHERE `id` = '" . $id . "'";
                $res = mysql_query($sql);
                if ($res) {
                    $contents .= '<h2>'. $lang['comment']. ' ID:' . $id . $lang['deleted'] . '</h2>';
                }
                break;
            default: // Update
                $sql = 'UPDATE `'.$forum_table.'` '.
                       'SET '.
                       "`title` = '".$title."', `comment` = '".$comment."', `user_name` = '".$user_name."', `user_uri` = '".$user_uri."', `color` = '".$color."', `mod` = '".$cmod."', `trash` = '".$mod_del."' ".
                       "WHERE `id` = '" . $id . "'";
                $res = mysql_query($sql);
                if ($res) {
                    $contents .= '<h2>'. $lang['comment']. ' ID:' . $id . $lang['updated'] . '</h2>';
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