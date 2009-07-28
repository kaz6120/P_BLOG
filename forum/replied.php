<?php
/**
 * Reply script
 *
 * $Id: forum/replied.php, 2006-06-04 23:38:03 Exp $
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

if (isset($_POST['user_name'], $_POST['title'], $_POST['color'],
          $_POST['user_pass'], $_POST['tid'], $_POST['parent_key'], $_POST['refer_id'])) {

    // comment field name
    $comment_field_name = md5($block_spam['comment_field_name']);

    // posted variables
    $user_name  = insert_safe($_POST['user_name']);
    $mail       = insert_safe($_POST['user_email']);
    $user_pass  = insert_safe($_POST['user_pass']);
    $title      = insert_tag_safe($_POST['title']);
    $title      = ($title == '') ? 'Re: ' : $title;
    $comment    = insert_tag_safe($_POST[$comment_field_name]);
    $color      = insert_safe(intval($_POST['color']));
    $tid        = insert_safe(intval($_POST['tid']));
    $parent_key = insert_safe(intval($_POST['parent_key']));
    $refer_id   = insert_safe(intval($_POST['refer_id']));

    // Unicode conversion
    if ($cfg['enable_unicode'] == 'on') {
        mb_convert_variables($cfg['mysql_lang'], "auto", $user_name, $title, $comment);
    }
    
    // Block Spams
    if ((isset($_POST['user_uri'])) && (substr_count($_POST['user_uri'], "@") >0) ||
        (substr_count($comment, "http://") > (int)$block_spam['uri_count']) ||
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

        // get user's remote host info
        if (!isset($_SERVER['REMOTE_HOST'])) {
            $_SERVER['REMOTE_HOST'] = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $re_host = $_SERVER['REMOTE_HOST'];
        } else {
            $re_host = $_SERVER['REMOTE_HOST'];
        }
    
        if (isset($_POST['user_uri'])) {
            $user_uri = $_POST['user_uri'];
        }

        // Format the date
        $fdate = gmdate('Y-m-d H:i:s', time() + ($cfg['tz'] * 3600));
        $cmod  = gmdate('YmdHis',      time() + ($cfg['tz'] * 3600));
        
        $sql = "INSERT INTO ".
               "{$forum_table}(".
               "`tid`, `parent_key`, `title`, `comment`, `user_name`, `user_pass`, ".
               "`user_mail`, `user_uri`, `color`, `date`, `mod`, `user_ip`, `refer_id`) ".
               "VALUES('".
               $tid."', '".$parent_key."', '".$title."', '".$comment."', '".$user_name."', md5('".$user_pass."'), '".
               $mail."', '".$user_uri."', '".$color."', '".$fdate."', '".$cmod."', '".$re_host."', '".$refer_id."')";
        $res = mysql_query($sql);
        if ($res) {
            // update the modification date of the parent log which is the index of the thread.
            $update_sql = "UPDATE `{$forum_table}` SET `mod` = '{$cmod}' WHERE `parent_key` = '1' AND `tid` = '{$tid}'";
            $update_res = mysql_query($update_sql);

            // Check the number of replies of the thread
            $rep_sql = "SELECT COUNT(`id`) FROM `{$forum_table}` WHERE `tid` = '{$tid}' AND `parent_key` = '0' AND `trash` = '0'";
            $rep_res = mysql_query($rep_sql);
            $rep_row = mysql_fetch_array($rep_res);
            $pn = ceil(($rep_row[0] + 1) / $cfg['pagemax']);
            $p  = floor(($pn - 1) * $cfg['pagemax']);
            // $query_to_thread = $row['tid'] .'&amp;p=0';
            $query_to_the_latest = 'tid='.$tid.'&p='.$p.'&pn='.$pn.'&pm='.$cfg['pagemax'].'#latest';
            header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] .$cfg['root_path']. 'forum/topic.php?'.$query_to_the_latest);
            exit;
        }
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] .$cfg['root_path']. 'forum/index.php');
    exit;
}
?>