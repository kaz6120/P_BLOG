<?php
/**
 * Modify Comment
 *
 * $Id: comment_edit.php, 2005/11/13 17:47:45 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once './include/fnc_search.inc.php';
require_once './include/fnc_forum.inc.php';

against_xss();


if (isset($_GET['id'])) {
    $id        = intval($_GET['id']);
    // Main Contents
    $sql = 'SELECT'.
           " `id`, `tid`, `parent_key`, `title`, `comment`, `user_name`, `user_pass`, `user_mail`, `user_uri`, `color`,".
           " DATE_FORMAT(`mod`, '%Y/%m/%d %T') as `mod`, `refer_id`".
           " FROM `{$forum_table}`".
           " WHERE (`id` = '{$id}')";
    $res  = mysql_query($sql);
    if (!$res) {
        die(mysql_error());
    } else {
        $rows = mysql_num_rows($res);
    }
    if ($rows) {
        while ($row = mysql_fetch_array($res)) {
            $row['id']        = sanitize(intval($row['id']));
            $row['tid']       = sanitize(intval($row['tid']));
            $row['user_name'] = sanitize($row['user_name']);
            $row['user_mail'] = sanitize($row['user_mail']);
            $row['user_uri']  = sanitize($row['user_uri']);
            $row['title']     = sanitize($row['title']);
            $row['color']     = sanitize(intval($row['color']));
            $row['comment']   = sanitize($row['comment']);
            
            $row = convert_to_utf8($row);

            // Init user info
            $user_ip   = '';
            $user_mail = '';
            $user_pass = '';
            
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
            
            $action     = './comment_edit.php';
            $input_pass = '<input accesskey="p" tabindex="9" type="password" name="user_pass" value="' . $user_pass .'" class="bordered" />';
            $parent_id  = '<input type="hidden" name="refer_id" value="' . $row['refer_id'] . '" />';
            
            $contents = '';
            
            // Load presentation templage
            require_once './contents/comment_edit.tpl.php';
            
            xhtml_output('forum');
        }
    } else {
        $contents = bad_req_error();
        xhtml_output('forum');
        exit;
    }

} elseif (isset($_POST['user_name'], $_POST['user_pass'],
                $_POST['title'], $_POST['comment'], $_POST['color'], $_POST['id'], $_POST['refer_id'], $_POST['mod_del'])) {
    $user_name = insert_safe($_POST['user_name']);
    $user_pass = insert_safe(md5($_POST['user_pass']));
    $title     = insert_tag_safe($_POST['title']);
    $comment   = insert_tag_safe($_POST['comment']);
    $color     = insert_safe(intval($_POST['color']));
    $id        = insert_safe(intval($_POST['id']));
    $refer_id  = insert_safe(intval($_POST['refer_id']));
    $mod_del   = insert_safe(intval($_POST['mod_del']));

    $check_sql = "SELECT `user_pass` FROM `{$forum_table}` WHERE `id` = '{$id}'";
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
        $sql = "UPDATE `{$forum_table}` ".
               'SET '.
               "`title` = '".$title."', `comment` = '".$comment."', `user_name` = '".$user_name."', `user_uri` = '".$user_uri."', `color` = '".$color."', `mod` = '".$cmod."', `trash` ='".$mod_del."' ".
               "WHERE `id` = '" . $id . "'";
        $res = mysql_query($sql);
        if ($res) {
            header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'article.php?id=' . urlencode($refer_id));
            exit;
        }
    } else {
        $contents = bad_req_error();
        xhtml_output('forum');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
    