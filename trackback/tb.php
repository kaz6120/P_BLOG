<?php
/**
 * Trackback impl (Receiving Ping)
 *
 * 2004/05/09 first implementation. 
 * @author   : imksoo / Kajiya Takeshi
 * @modified : kaz
 *
 * $Id: tb.php, 2006-08-12 15:22:58 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';

session_control();

$error   = 0;
$message = '';

if ($cfg['trackback'] == 'off') {
    $error = 1;
    $message = 'Trackback denied.';
} elseif (!isset($_GET['id'])) {
    $error = 1;
    $message = "You must set blog id!";
} else {

    $id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $title   = $_POST['title'];
        $excerpt = $_POST['excerpt'];
        $url     = $_POST['url'];
        $name    = $_POST['blog_name'];
    // Receiving Ping form MT doesn't work without this 
    } elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
        if (isset($_GET['title'], $_GET['excerpt'], $_GET['url'], $_GET['blog_name'])) {
            $title   = $_GET['title'];
            $excerpt = $_GET['excerpt'];
            $url     = $_GET['url'];
            $name    = $_GET['blog_name'];
        }
    } else {
        $title   = '';
        $excerpt = '';
        $url     = '';
        $name    = '';
    }
    
    // Deny when required values are empty
    $root_dir = $cfg['root_path'];
    $root_dir = str_replace('/', '\/', $root_dir);
    $root_dir = str_replace('.', '\.', $root_dir);
    
     if (empty($url)     or empty($title) or
         empty($excerpt) or empty($name)  or ($url == 'http://')) {
        $error = 1;
        $message = 'Bad Request.';
        header('Location: '.$http.'://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].'article.php?id='.urlencode($id));
    } else {
        mb_convert_variables($cfg['mysql_lang'], "UTF-8,EUC-JP,Shift_JIS,ASCII", $title, $excerpt, $url, $name);
        $blog_id = insert_safe(intval($_GET['id']));
        $title   = insert_safe($title);
        $excerpt = insert_safe($excerpt);
        $url     = insert_safe($url);
        $name    = insert_safe($name);

        // Block Spam
        if ((substr_count($url, "/") < 3) || 
            ((substr_count($url, "/") == 3) && (substr($url, -1) == "/")) ||
            (preg_match($block_spam['keywords'], $title)) ||
            (preg_match($block_spam['keywords'], $excerpt)) ||
            (preg_match($block_spam['keywords'], $url)) ||
            (preg_match($block_spam['keywords'], $name)) ||
            (($block_spam['deny_1byteonly'] == 'yes') && 
             (!preg_match('/.*[\x80-\xff]/', $excerpt)))
           ) {
            //echo 'You Are A Spammer!';
            header('Location: '.$cd.'/article.php?id='.$blog_id);
            exit;
        }
        
        if (file_exists($cd . '/include/user_include/plugins/plg_trackback_spam_blocker.inc.php')) {
            include_once $cd . '/include/user_include/plugins/plg_trackback_spam_blocker.inc.php';
            if (class_exists('P_BLOG_TrackbackSpamBlocker')) {
                $tbBlock = new P_BLOG_TrackbackSpamBlocker;
                $tbBlock->denyTrackbackWithoutRef($blog_id);
            }
        }
        
        // Deny Ping from the same page
        $check_sql  = 'SELECT COUNT(id) as num FROM ' . $trackback_table . 
                     " WHERE (blog_id = '{$blog_id}') AND (url = '{$url}')";
        $check_res  = mysql_query($check_sql);
        $check_row  = mysql_fetch_array($check_res);
        
        // Deny Ping with same comment
        $check_sql2 = 'SELECT COUNT(id) as num FROM ' . $trackback_table . 
                     " WHERE (title = '{$title}') OR (url = '{$url}') OR (excerpt = '{$excerpt}')";
//                     " WHERE excerpt = '{$excerpt}'";
        $check_res2 = mysql_query($check_sql2);
        $check_row2 = mysql_fetch_array($check_res2);
        
        if (($check_row['num']  == 0) &&
            ($check_row2['num'] == 0)) {
//        if ($check_row['num']  == 0) {
            $fdate = gmdate('Y-m-d H:i:s', time() + ($cfg['tz'] * 3600));
            $sql = 'INSERT INTO ' . $trackback_table . ' (blog_id, title, excerpt, url, name, date)' .
                   " VALUES ({$blog_id}, '{$title}', '{$excerpt}', '{$url}', '{$name}', '{$fdate}') ";

            $res = mysql_query($sql);
            if ($res) {
                $error = 0;
                $message = 'Ping received.';
            } else {
                $error = 1;
                $message = 'internal error!';
            }
            
        } else {
            $error = 1;
            $message = 'Ping denied.';
        }
    }
}

echo <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<response>
<error>{$error}</error>
<message>{$message}</message>
</response>
EOD;

?>