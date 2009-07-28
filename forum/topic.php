<?php
/**
 * Display Topic
 * 
 * $Id: topic.php, 2005/02/03 15:44:59 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/search_plus.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once './include/fnc_search.inc.php';
require_once './include/fnc_forum.inc.php';

require_once $cd . '/include/fnc_logs.inc.php';

session_control();

against_xss();

//=====================================================
// PREPARE SEARCH QUERY
//=====================================================

if (empty($_GET['k'])) { $_GET['k'] = ''; }
if (empty($_GET['p'])) { $_GET['p'] = '0'; }
if (empty($_GET['d'])) { $_GET['d'] = ''; }
if (empty($_GET['c'])) { $_GET['c'] = '0'; }
if (empty($_GET['pn'])) { $_GET['pn'] = '1'; }

if (isset($_GET['k'], $_GET['p'], $_GET['d'], $_GET['c'], $_GET['pn'], $_GET['tid'])) {

    if ($cfg['enable_unicode'] == 'on') {
        $keyword = mb_convert_encoding(trim($_GET['k']), $cfg['mysql_lang'], "auto");
    } else {
        $keyword = trim($_GET['k']);
    }
    $page  = $_GET['p'];
    $date  = $_GET['d'];
    $case  = $_GET['c'];
    $tid   = $_GET['tid'];
    if (($page != '') & ($case != '')) {
        $sql = 'SELECT'.
               " `id`, `tid`, `parent_key`, `title`, `comment`, `user_name`, `user_pass`, `user_uri`, `user_mail`, `color`, `date`,".
               " DATE_FORMAT(`mod`, '%Y/%m/%d %T') as `mod`, `refer_id`".
               ' FROM `'.$forum_table.'`'.
               " WHERE `tid` = '". $tid . "' AND `trash` = '0'".
               " ORDER BY `date` ASC LIMIT {$page}, {$cfg['pagemax']} ";
    }
    //=====================================================
    // SUBMIT SEARCH QUERY
    //=====================================================
    $res = mysql_query($sql);

    if ($res) {
        $count_sql = "SELECT `id` FROM `{$forum_table}` WHERE `tid` = '{$tid}' AND `trash` = '0'";
        $hit_res = mysql_query($count_sql);
        $hit_row = mysql_num_rows($hit_res);
    
        // Show the hit data info.
        $rows = mysql_num_rows($res);
        if ($cfg['enable_unicode'] == 'on') {
            $keyword = mb_convert_encoding($keyword, "auto", $cfg['mysql_lang']);
        }
        //=================================================
        // SHOW THE RESULTS!
        //=================================================
        
        if ($hit_row) {
            /*
            $sql = "SELECT DATE_FORMAT(`date`,'%Y-%m') as `date` FROM `{$forum_table}` GROUP BY `date` ORDER BY `date`";
            if ($cfg['date_order_desc'] == 'yes') {
                $sql.= ' DESC';
            }
            */
            $rep_sql = "SELECT COUNT(`id`) FROM `{$forum_table}` WHERE `tid` = '{$tid}' AND `parent_key` = '0' AND `trash` = '0'";
            $rep_res = mysql_query($rep_sql);
            $rep_row = mysql_fetch_array($rep_res);
            // When the post number is larger than the page max
            if (($rep_row[0] + 1) > $cfg['pagemax']) {
                // Generate the link to the latest post in the last page.
                // p=  means the first post of each pages.
                // pn= means page number
                $pn = ceil(($rep_row[0] + 1) / $cfg['pagemax']);
                $p  = floor(($pn - 1) * $cfg['pagemax']);
                $query_to_the_latest = $tid . '&amp;p='.$p.'&amp;pn='.$pn.'&amp;pm='.$cfg['pagemax'];
            } else { // When the post number is smaller than the page max.
                $query_to_the_latest = $tid . '&amp;p=0';
            }
            $navi_menu =<<<EOD
<ul class="flip-menu">
<li><a href="./index.php" accesskey="i">{$lang['topic_list']}</a></li>
<li><a href="./add.php" accesskey="n">{$lang['new_topic']}</a></li>
<li><a href="./topic.php?tid={$query_to_the_latest}#latest" accesskey="l">{$lang['latest']}</a></li>
</ul>
EOD;
            // display the number of the thread
            $disp_page = $page + 1;
            $disp_rows = $page + $rows;
            if (($hit_row <= 1) && ($cfg['xml_lang'] == 'en')) {
                $lang_comments = 'comment.';
            } else {
                $lang_comments = $lang['comments'];
            }
            $contents =<<<EOD
{$navi_menu}
<p class="search-res">
<span class="search-res">{$hit_row}</span> {$lang_comments} 
<span class="search-res">{$disp_page} - {$disp_rows}</span> / <span class="search-res">{$hit_row}</span>
</p>
EOD;
            
            $flip_link = display_page_flip();
            $contents .= $flip_link;
            
            // display topic list table
            while ($row = mysql_fetch_array($res)) {
                // check the number of replies
                /*
                $rep_sql = "SELECT COUNT(`id`) FROM `{$forum_table}` WHERE `tid` = '{$row['tid']}' AND `parent_key` = '0' AND `trash` = '0'";
                $rep_res = mysql_query($rep_sql);
                $rep_row = mysql_fetch_array($rep_res);
                */
                $row = convert_to_utf8($row);
                $contents .= display_forum_log_box($row);

            }
            
            //$contents .= $flip_link;
            
            // Preparation for the form template
            if (isset($_GET['qid'])) {
                $qid = intval($_GET['qid']);
                $sql = "SELECT `title`, `refer_id`, `comment` FROM `{$forum_table}` WHERE `tid` = '{$tid}' AND `id` = '{$qid}'";
                $res = mysql_query($sql);
                $row = mysql_fetch_array($res);
                $row = convert_to_utf8($row);
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
                    $row['comment'] = '[q1]'.$row['comment'].'[/q1]';
                }
                $quoted_comment = $row['comment'];
            } else {    
                $sql = "SELECT `title`, `refer_id` FROM `{$forum_table}` WHERE `tid` = '{$tid}' AND `parent_key` = '1'";
                $res = mysql_query($sql);
                $row = mysql_fetch_array($res);
                $row = convert_to_utf8($row);
                $quoted_comment = $lang['no_tags_allowed'];
            }
            $reply_title = 'Re: ' . preg_replace('/^Re:/', '', htmlspecialchars($row['title']));
            $post_title = '';
            $title      = $reply_title;
            $comment    = $quoted_comment;
            $action = './replied.php';
            // comment field name
            $comment_field_name = md5($block_spam['comment_field_name']);
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
            $parent_key = '<input type="hidden" name="parent_key" value="0" />'."\n".
                          '<input type="hidden" name="tid" value="' . $tid . '" />';
            $refer_id   = $row['refer_id'];
            
            include_once './contents/comment_form.tpl.php';
            $contents .= $comment_form;
            
            $contents .= $flip_link;
            $contents .= $navi_menu;

            xhtml_output('forum');

        } else {
            header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/index.php');
            exit;
        }
        
    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/index.php');
    exit;
}
?>
