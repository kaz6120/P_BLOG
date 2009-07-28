<?php
/**
 * Reply to the Forum
 *
 * $Id: forum/reply.php, 2004/12/20 10:57:38 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_search.inc.php';
require_once './include/fnc_forum.inc.php';

session_control();


if (isset($_GET['tid'])) {
    if (isset($_GET['qid']) && ($_GET['qid'] != '')) {
        $tid = intval($_GET['tid']);
        $qid = intval($_GET['qid']);
        $sql = 'SELECT * FROM `'.$forum_table.'` WHERE tid = ' . $tid . ' AND id = ' . $qid;
        $res = mysql_query($sql);
        $row = mysql_fetch_array($res);
    } else {
        $tid = intval($_GET['tid']);
        $sql = 'SELECT * FROM `'.$forum_table.'` WHERE tid = ' . $tid . ' AND parent_key = 1';
        $res = mysql_query($sql);
        $row = mysql_fetch_array($res);
    }
    if ($row) {
        $row = convert_to_utf8($row);
        $reply_title = 'Re: ' . preg_replace('/^Re:/', '', $row['title']);
        
        // generate quote box tags
        if (isset($qid)) {
            if (preg_match('/\[q2\]/', $row['comment'])) {
                $row['comment'] = '[q3]' .$row['comment']. '[/q3]';
            } elseif (preg_match('/\[q1\]/', $row['comment'])) {
                $row['comment'] = '[q2]' .$row['comment']. '[/q2]';
            } else {
                $row['comment'] = '[q1]' .$row['comment']. '[/q1]'; 
            }
        } else {
            $row['comment'] = '';
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
        $hn         = 'h2';            // Header level of comment form
        $action     = './replied.php'; // Action target
        $post_title = '';
        $title      = $reply_title;
        $comment    = $row['comment'];
        $parent_key = '<input type="hidden" name="parent_key" value="0" />'."\n".
                      '<input type="hidden" name="tid" value="' . $tid . '" />';
        $refer_id   = $row['refer_id'];
        
        // Load "Comment Form" template
        require_once './contents/comment_form.tpl.php';
        $comments = <<<EOD
<ul class="flip-menu">
<li><a href="./index.php" accesskey="i">{$lang['topic_list']}</a></li>
<li><a href="./add.php" accesskey="n">{$lang['new_topic']}</a></li>
<li><a href="./topic.php?tid={$tid}" accesskey="b">{$lang['back_to_topic']}</a></li>
</ul>
{$comment_form}
EOD;
     }
} else {
    $comments = '<h2>Topic Not Found.</h2>';
}

$contents = $comments;

xhtml_output('forum');

?>