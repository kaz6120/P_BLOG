<?php
/**
 * Post New Comment
 *
 * $Id: forum/add.php, 2006-05-27 19:26:51 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_search.inc.php';
require_once './include/fnc_forum.inc.php';

session_control();


if (isset($_GET['refer_id'])) {
    $refer_id  = $_GET['refer_id'];
    $refer_sql = 'SELECT name FROM ' . $log_table . ' WHERE id = ' . $refer_id;
    $refer_res = mysql_query($refer_sql);
    if ($row = mysql_fetch_array($refer_res)) {  
        $row = convert_to_utf8($row);         
        $refer_log_title = 'Re: ' . $row['name'];
        $post_title = '<h3>&#187; <a href="../article.php?id=' . $refer_id . '">' . $row['name'] . "</a></h3>\n";
    }
} else {
    $refer_log_title = '';
    $refer_id        = '0';
    $post_title      = '';
}

// Cookies
if (isset($_COOKIE['p_blog_forum_user'])) {
    $user_name = $_COOKIE['p_blog_forum_user'];
    $checked   = ' checked="checked"';
} else {
    $user_name = '';
    $checked   = '';
}
if (isset($_COOKIE['p_blog_forum_email'])) {
    $user_email = $_COOKIE['p_blog_forum_email'];
} else {
    $user_email = '';
}
if (isset($_COOKIE['p_blog_forum_uri'])) {
    $user_uri = $_COOKIE['p_blog_forum_uri'];
} else {
    $user_uri = '';
}

// Setting For Template...
$action     = './added.php';    // Action target
$title      = $refer_log_title; // Title
$comment    = $lang['no_tags_allowed'];
$parent_key = '';               // Parent key is not needed because this is the first post
// comment field name
$comment_field_name = md5($block_spam['comment_field_name']);

// Load "Comment Form" template
require_once './contents/comment_form.tpl.php'; 

// Contents
$contents =<<<EOD

<ul class="flip-menu">
<li><a href="./index.php" accesskey="i">{$lang['topic_list']}</a></li>
<li><span class="cur-tab">{$lang['new_topic']}</span></li>
</ul>
{$comment_form}
EOD;

xhtml_output('forum');

?>