<?php
/**
 * Add New Topic
 *
 * $Id: forum/added.php, 2006-06-04 23:39:04 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_search.inc.php';
require_once './include/fnc_forum.inc.php';

// Block Spams
if (($_POST['comment_title'] != '') || ($_POST['name'] != '') || 
    ($_POST['mail'] != '') || ($_POST['address'] != '') || 
    ($_POST['comment'] != '') || ($_POST['url_key'] != '')) {

    // echo 'Hi, Spammer! :-P';
    header('Location: '.$cd.'/forum/index.php');
    exit;
}

if (isset($_POST['user_name'], $_POST['title'],
          $_POST['color'], $_POST['user_pass'], $_POST['refer_id'])) {

    // comment field name
    $comment_field_name = md5($block_spam['comment_field_name']);

    $user_name = insert_safe($_POST['user_name']);
    $mail      = insert_safe($_POST['user_email']);
    $title     = insert_tag_safe($_POST['title']);
    $comment   = insert_tag_safe($_POST[$comment_field_name]);
    $color     = insert_safe($_POST['color']);
    $user_pass = insert_safe($_POST['user_pass']);
    $refer_id  = insert_safe(intval($_POST['refer_id']));

    // Unicode conversion
    if ($cfg['enable_unicode'] == 'on') {
        mb_convert_variables($cfg['mysql_lang'], 'auto', $user_name, $title, $comment);
    }
        
    // If title is empty
    $title = ($title == '') ? 'Untitled' : $title;
    
    // Block Spams
    if ((isset($_POST['user_uri'])) && (substr_count($_POST['user_uri'], "@") >0) ||
        (substr_count($comment, "http://") >= (int)$block_spam['uri_count']) ||
        (preg_match($block_spam['tags'], $_POST[$comment_field_name])) ||
        (preg_match($block_spam['keywords'], $_POST[$comment_field_name])) ||
        (($block_spam['deny_1byteonly'] == 'yes') && 
         (!preg_match('/.*[\x80-\xff]/', $_POST[$comment_field_name]))) ||
        (preg_match($block_spam['tags'],  $_POST['title'])) ||
        (check_spammer() > 0)
       ) {
        // echo 'Hi, Spammer! :-p';
        header('Location: '.$cd.'/forum/index.php');
        exit;
    }
    
    // Deny comment with same content
    $check_sql = 'SELECT COUNT(id) as num FROM ' . $forum_table . 
                 " WHERE comment = '{$comment}'";
    $check_res = mysql_query($check_sql);
    $check_row = mysql_fetch_array($check_res);
    if ($check_row['num'] > 1) {
        header('Location: '.$cd.'/forum/index.php');
        exit;
    }
    
    // Matching a valid User password
    if (!preg_match('/^[0-9a-zA-Z]{4,16}$/i', $_POST['user_pass'])) {
        $contents = '<h2>'.$lang['invalid_pass'].'</h2>'.
                    '<p class="warning">'.$lang['invalid_pass_msg'].'</p>';
        xhtml_output('forum');
        exit;
    } elseif ($_POST[$comment_field_name] == '') {
        $contents = "<h2>Ooops.</h2>\n".'<p class="warning">'.$lang['no_comment']."</p>\n";
        xhtml_output('forum');
        exit;
    } else {
        // Get remote host info
        if (!isset($_SERVER['REMOTE_HOST'])) {
            $re_host = $_SERVER['REMOTE_ADDR'];
        } else {
            $re_host = $_SERVER['REMOTE_HOST'];
        }
        
        if (isset($_POST['user_uri'])) {
            $user_uri = $_POST['user_uri'];
        }
    
        // Check the max value of thread ID in database, and then 
        // plus "1" to the ID of the new thread.
        $get_id_sql    = "SELECT MAX(`tid`) FROM `{$forum_table}`"; 
        $max_id_res    = mysql_query($get_id_sql);
        $max_id_row    = mysql_fetch_array($max_id_res);
        $new_tid       = $max_id_row[0] + 1;

        // Format the date
        $fdate = gmdate('Y-m-d H:i:s', time() + ($cfg['tz'] * 3600));
        $cmod  = gmdate('YmdHis',      time() + ($cfg['tz'] * 3600));
        
        $sql = "INSERT INTO {$forum_table}(`tid`, `title`, `comment`, `user_name`, `user_pass`, `user_mail`, `user_uri`, `color`, `date`, `mod`, `user_ip`, `refer_id`) ".
               "VALUES('".$new_tid."', '".$title."', '".$comment."', '".$user_name."', md5('".$user_pass."'), '".$mail."', '".$user_uri."', '".$color."', '".$fdate."', '".$cmod."', '".$re_host."', '".$refer_id."')";
        $res = mysql_query($sql);
        if ($res) {
            // Move to the new thread
            $tid = $new_tid;
            $query_to_the_latest = 'tid='.$tid.'&p=0#latest';
            header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] .$cfg['root_path']. 'forum/topic.php?'.$query_to_the_latest);
            exit;
        }
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] .$cfg['root_path']. 'forum/index.php');
    exit;
}
?>