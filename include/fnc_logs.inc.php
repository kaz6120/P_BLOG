<?php
/**
 * Functions for LOGS
 *
 * $Id: fnc_logs.inc.php, 2005/11/25 22:42:05 Exp $
 */

require_once $cd . '/trackback/include/fnc_trackback.inc.php';

//================================================================
// MENU-BOX
//================================================================

/**
 * Recent Entries
 * @author : nakamuxu
 */
function display_recent_entries() 
{
    global $cfg, $lang, $log_table, $row;
    
    if ($cfg['show_pre_recent_menu'] == 'yes') {
        $entry_list = '';
        $sql = 'SELECT'.
               ' `id`, `name`, `date`, `category`'.
               ' FROM `' . $log_table . "` WHERE `draft` = '0'".
               " ORDER BY `date` DESC LIMIT ".$cfg['pagemax'].', '.$cfg['pre_recent_max'];
        $res  = mysql_query($sql);
        $rows = mysql_num_rows($res);
        if ($rows != '0') {
            while ($row = mysql_fetch_array($res)) { 
                $row = convert_to_utf8($row);
                $entry_list .= '<li>'.
                               '<a href="article.php?id='.$row['id'].'" title="'.$lang['category'].' : &quot;'.htmlspecialchars($row['category']).'&quot;">'.
                               $row['name'].'</a>'.
                               "</li>\n";
            }
        } else {
            $entry_list .= '<li>-</li>';
        }
        // Presentation!
        $recent_entries =<<<EOD
<div class="menu" id="recent-entries">
<h2>{$lang['previous']}{$rows}{$lang['logs']}</h2>
<ul>
{$entry_list}</ul>
</div>
EOD;
    } else {
        $recent_entries = '';
    }
    return $recent_entries;
}



/**
 * Recent Comments
 */
function display_recent_comments() 
{
    global $cfg, $lang, $log_table, $forum_table, $row;

    if ($cfg['show_recent_comment'] == 'yes') {
        $comments_list = '';
        $sql = 'SELECT'.
               " `id`, `tid`, `title`, `user_name`, DATE_FORMAT(`date`, '%Y/%m/%d %T') as `date`, `refer_id`".
               " FROM `{$forum_table}` WHERE `trash` = '0'".
               " ORDER BY `date` DESC LIMIT " . $cfg['recent_comment_max'];
        $res  = mysql_query($sql);
        $rows = mysql_num_rows($res);
        if ($rows != '0') {
            while ($row = mysql_fetch_array($res)) {
                $row = convert_to_utf8($row);
                if ($row['refer_id'] == '0') {
                    // Check the number of replies
                    $rep_sql = "SELECT COUNT(`id`) FROM `{$forum_table}` WHERE `tid` = '{$row['tid']}' AND `parent_key` = '0' AND `trash` = '0'";
                    $rep_res = mysql_query($rep_sql);
                    $rep_row = mysql_fetch_array($rep_res);
                    if (($rep_row[0] + 1) > $cfg['topic_max']) {
                        $pn = ceil(($rep_row[0] + 1) / $cfg['topic_max']);
                        $p  = floor(($pn - 1) * $cfg['topic_max']);
                        $target_id = 'forum/topic.php?tid='.$row['tid'] .'&amp;p='.$p.'&amp;pn='.$pn.'&amp;pm='.$cfg['topic_max'].'#latest';
                    } else {
                        $target_id = 'forum/topic.php?tid='.$row['tid'] .'&amp;p=0';
                    }
                } else {
                    $target_id = 'article.php?id='.$row['refer_id'].'#c'.$row['id'];
                }
                $comment_title = htmlspecialchars($row['title']);
                $comments_list .= '<li><a href="'.$target_id.'" title="&quot;'.$comment_title.'&quot;">'.
                                  //$comment_title . '<br />'.
                                  'From '. htmlspecialchars($row['user_name']) . '<br />@' . $row['date'] . '</a>'.
                                  "</li>\n";
             }
        } else {
            $comments_list .= '<li>-</li>';
        }

        // Presentation!
        $recent_comments =<<<EOD
<div class="menu" id="recent-comments">
<h2>{$lang['recent_comments']}</h2>
<ul>
{$comments_list}</ul>
</div>
EOD;
    } else {
        $recent_comments = '';
    }
    return $recent_comments;
}


/**
 * Recent Trackbacks
 */
function display_recent_trackbacks() 
{
    global $cfg, $lang, $trackback_table, $row;
    
    if ($cfg['show_recent_trackback'] == 'yes') {
        $trackback_list = '';
        $sql = 'SELECT'.
               " `id`, `blog_id`, `title`, `name`, DATE_FORMAT(`date`, '%Y/%m/%d %T') as `date`".
               ' FROM `'.$trackback_table.'`'.
               " ORDER BY `date` DESC LIMIT " . $cfg['recent_trackback_max'];
        $res  = mysql_query($sql);
        $rows = mysql_num_rows($res);
        if ($rows != '0') {
            while ($row = mysql_fetch_array($res)) {
                $row = convert_to_utf8($row);
                $trackback_title = htmlspecialchars($row['title']);
                $trackback_list .= '<li><a href="article.php?id='.$row['blog_id'].'#tb'.$row['id'].'" title="&quot;'.$trackback_title.'&quot;">'.
                                   //$trackback_title . '<br />'. 
                                   'From ' . htmlspecialchars($row['name']) . '<br />@' . $row['date'] . '</a>'.
                                   "</li>\n";
            }
        } else {
            $trackback_list .= '<li>-</li>';
        }

        //////////////// Presentation! /////////////////
        $recent_trackbacks =<<<EOD
<div class="menu" id="recent-trackbacks">
<h2>{$lang['recent_trackbacks']}</h2>
<ul>
{$trackback_list}</ul>
</div>
EOD;
    } else {
        $recent_trackbacks = '';
    }
    return $recent_trackbacks;
}


//================================================================
// CONTENT-BOX
//================================================================

/**
 * Article Box
 */
function display_article_box($row) 
{
    global $cfg, $lang, $cd, $session_status, $id, $admin_dir, $article_addition;

    // Permanent Link
    if (empty($id)) {
        $permalink = '<a href="'.$cd.'/article.php?id='.$row['id'].'" title="'.
                     $lang['permalink_title_1'] . htmlspecialchars(strip_tags($row['name'])) . $lang['permalink_title_2'].
                     '" rel="Bookmark">Permalink</a> ';
        $read_more = '<p class="read-more"><a href="' . $cd . '/article.php?id=' . $row['id'] . '" title="' . $row['name'] . '">' . $lang['more'] . '</a></p>';
        $row['comment'] = preg_replace('/<!-- ?more ?-->.*<!-- ?\/more ?-->/is', $read_more, $row['comment']);
        $row['comment'] = preg_replace('/<!-- ?more ?-->.*/is', $read_more, $row['comment']);
    } else {
        $permalink = '';
    }

    if (file_exists($cd . '/include/user_include/plugins/plg_isbn.inc.php')) {
        include_once $cd . '/include/user_include/plugins/plg_isbn.inc.php';
        $FKMM_isbn = new FKMM_isbn();
        $row['comment'] = $FKMM_isbn->convert_isbn($row['comment']);
    }
    
    // Convert Text to XHTML
    if (file_exists($cd . '/include/user_include/plugins/plg_markdown.inc.php')) {
        include_once $cd . '/include/user_include/plugins/plg_markdown.inc.php';
        $FKMM_markdown = new FKMM_markdown();
        $row['comment'] = $FKMM_markdown->convert($row['comment']);
    } else {
        $row['comment'] = xhtml_auto_markup($row['comment']);
    }

    // Convert Enclosure
    if (file_exists($cd . '/rss/include/P_BLOG_RSS.class.php')) {
        include_once $cd . '/rss/include/P_BLOG_RSS.class.php';
        $p_rss = new P_BLOG_RSS;
        $row['comment'] = $p_rss->convertEnclosure($row['comment']);
    }
    
    $row['comment'] = preg_replace('/src="\./', 'src="' . $cd, $row['comment']);

    // Smiley
    $row = smiley($row);

    // Article title
    if (($row['href']) == "http://") {
        $article_title = $row['name'];
    } else {
        $article_title = '<a href="'.$row['href'].'">' . $row['name'] . "</a>\n";
    }

    // Time format
    if ($cfg['show_date_title'] == 'yes') {
        switch($cfg['date_style']) {
            case '1':
                $df = 'Y/m/d';
                break;
            case '2':
                $df = 'M d, Y';
                break;
            default:
                $df = 'Y-m-d';
                break;
        }
        $row['date'] = date($df.' G:i:s', strtotime($row['date']));
        $row['mod']  = date($df.' G:i:s', strtotime($row['mod']));
    }
    if ($row['date'] != $row['mod']) {
        $row['date']  = date('G:i:s', strtotime($row['date']));
        $mod_str = ', '.$lang['mod'].' @ '.$row['mod'];
    } else {
        $row['date']  = date('G:i:s', strtotime($row['date']));
        $mod_str = '';
    }

    // Category
    $category_title = $lang['cat_title_1'] . $row['category'] . $lang['cat_title_2'];
    $category = '<a href="'.$cd.'/category.php?k='.urlencode($row['category']).'" title="'.$category_title.'">'.$row['category'].'</a>';
    
    // Show date time
    if ($cfg['show_date_time'] == 'yes') {
        $date_time = '<div class="date">' . $lang['post'] . ' @ ' . $row['date'] . ' ' . $mod_str . ' | ' . $category . "</div>\n";
    } else {
        $date_time = '';
    }

    // Show e-mail link
    if ($cfg['use_email_link'] == 'yes') {
        $via_email_title = $lang['via_email_title_1'].htmlspecialchars(strip_tags($row['name'])).$lang['via_email_title_2'];
        $email_link = '<a href="'.$cd.'/var/feedback/index.php?id=feedback&amp;a_id=' . $row['id'] . '" title="'.$via_email_title.'">Email</a>';
    } else {
        $email_link = '';
    }

    // Show comment link
    if (($cfg['use_comment_link'] == 'yes') && (@file_exists('./forum/index.php'))) {
        switch ($cfg['comment_style']) {
            case '2':
                $comment = post_comment(); // "Comment" style
                break;
            case '1':
                $comment = post_comment_forum(); // "Forum" style
                break;
            default :
                $comment = post_comment();
                break;
        }
    } else {
        $comment = '';
    }
        
    // Show trackbacks
    if ($cfg['trackback'] == 'on') {
        $trackback = display_trackback($row);
    } else {
        $trackback = '';
    }

    // Show "Modify or Delete" button when Admin mode.
    if ($session_status == 'on') {
        if ($row['draft'] == '1') {
            $update_target = 'draft_update';
        } else {
            $update_target = 'update';
        }
        $admin_button =<<<EOD
<form action="{$cd}/{$admin_dir}/{$update_target}.php" method="post">
<div class="submit-button">
<input type="hidden" name="id" value="{$row['id']}" />
<input type="hidden" name="mode" value="log" />
<input type="hidden" name="post_username" value="" />
<input type="hidden" name="post_password" value="" />
<input type="submit" tabindex="1" accesskey="m" value="{$lang['mod_del']}" />
</div>
</form>
EOD;
    } else {
        $admin_button = '';
    }
    
    // Article footer
    if (!empty($id)) { // When Permalink
        if ($email_link != '') {
            $email_link =<<<EOD
<div class="a-footer">
{$email_link}
</div>
EOD;
        } else {
            $email_link = '';
        }
        $article_footer =<<<EOD
{$email_link}{$article_addition}
{$trackback}
{$comment}
{$admin_button}
EOD;
    } else { // When Index
        $article_footer =<<<EOD
<div class="a-footer">
{$permalink}{$email_link}
{$trackback}
{$comment}{$admin_button}
</div>
EOD;
    }
    //////////////// Presentation! /////////////////
    $article_box =<<<EOD
<div class="section">
<h3 class="article-title">{$article_title}</h3>
{$date_time}<div class="comment">
{$row['comment']}
</div>
{$article_footer}
</div><!-- End .section -->

EOD;

    return $article_box;
}


/**
 * Post comment link
 */
function post_comment() 
{
    global $cd, $cfg, $lang, $id, $row, $session_status, $row, $trows, $comment_class, $forum_table, $block_spam;
    
    $tsql = "SELECT `id`, `tid`, `user_name`, `user_uri`, `title`, `comment`, `date`, `color`, `trash` FROM `{$forum_table}`".
            " WHERE (`refer_id` = '{$row['id']}') AND (`trash` = '0') ORDER BY `date` ASC";
    $tres = mysql_query($tsql);
    if (!$tres) {
        return ' Comment : Off';
        exit;
    }    
    $trow = mysql_num_rows($tres);
    
    if ($trow == '0') {
        $cstr = 'Comment';
    } elseif ($trow == '1') {
        $cstr = $trow . ' Comment';
    } else {
        $cstr = $trow . ' Comments';
    }

    // comment field name
    $comment_field_name = md5($block_spam['comment_field_name']);

    if ($trow != 0) { // When there are some comments...
        $comments = '';
        while ($trows = mysql_fetch_array($tres)) {    
            $trows['title']     = htmlspecialchars(utf8_convert($trows['title']));
            $trows['comment']   = nl2p(htmlspecialchars(utf8_convert($trows['comment'])));
            $trows['user_name'] = htmlspecialchars(utf8_convert($trows['user_name']));
            $class_order = array_keys($comment_class);
            $color_class = $class_order[$trows['color']];
            
            // If user's website URI was posted, wrap the user name with anchor.
            if (isset($trows['user_uri']) && preg_match('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)/', $trows['user_uri'])) {
                $user_name = '<a href="' . $trows['user_uri'] . '" rel="nofollow">' . $trows['user_name'] . '</a>';
            } else {
                $user_name = $trows['user_name'];
            }
            // Smiley!
            $trows = smiley($trows);
            $comments .= '<h5 id="c'.$trows['id'].'">'. $trows['title'] . "</h5>\n".
                         '<div class="' . $color_class . '">'."\n".
                         $trows['comment'] .
                         '<p class="author">From : ' . $user_name . ' @ ' . $trows['date'] . ' ' .
                         '<span class="edit"><a href="./forum/comment_edit.php?id=' . $trows['id'] . '">' . $lang['edit'] . '</a></span>'.
                         "</p>\n".
                         "</div>\n";
            // Admin button
            if ($session_status == 'on') {
                 $comments .= '<form action="./forum/admin/comment_edit.php" method="post">'."\n".
                              '<div class="submit-button">'."\n".
                              '<input type="hidden" name="edit" value="1" />'."\n".
                              '<input type="hidden" name="id" value="'.$trows['id'].'" />'."\n".
                              '<input type="submit" value="'.$lang['mod_del'].'" />'."\n".
                              '</div>'."\n".
                              '</form>'."\n";
            }             
            $tid = $trows['tid'];      
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
                
        // Settings for "Comment Form Template"
        $post_title = '';
        $title      = 'Re: ' . $row['name'];
        $comment    = $lang['no_tags_allowed'];
        $action     = './forum/comment_reply.php';
        $refer_id   = $row['id'];

                
        // Set parent key = 0 since parent comment is already posted,
        // and specify the topic id.
        $parent_key = '<input type="hidden" name="parent_key" value="0" />'."\n".
                      '<input type="hidden" name="tid" value="' . $tid . '" />';
    
        $comment_title = $lang['view_com_title_1'] . htmlspecialchars(strip_tags($row['name'])) . $lang['view_com_title_2'];
        $comment_link = '<a href="./article.php?id=' . $row['id'] . '#comments" title="'.
                        $comment_title.'" class="status-on">' . $cstr . '</a> ';
    } else { // When No Comment...
        
        // Settings for "Comment Form Template"
        $post_title = '';
        $action     = './forum/comment_reply.php';
        $refer_id   = $row['id'];
        // Initialize user info because it's the first time post
        $user_name  = '';
        $user_email = '';
        $user_uri   = '';
        $title      = 'Re: ' . $row['name'];
        $comment    = $lang['no_tags_allowed'];
        $checked    = '';
        // Set parent key = 1
        $parent_key = '<input type="hidden" name="parent_key" value="1" />';
        $comments   = '<p class="gray-out">No Comments</p>';

        $comment_title = $lang['post_com_title_1'] . htmlspecialchars(strip_tags($row['name'])) . $lang['post_com_title_2'];
        $comment_link = '<a href="./article.php?id=' . $row['id'] . '#comments" title="'.$comment_title.'">'.
                        'Post Comment</a> ';
        $tid      = '';
    }


    // Load the presentation template of "Comment Form"
    $comment_form = ''; // Initialize comment form
    require_once $cd . '/forum/contents/comment_form.tpl.php';
    $comment_list =<<<EOD
<!-- Begin #comment-list -->
<div id="comment-list">
<h4 id="comments">{$cstr}</h4>
{$comments}
{$comment_form}
</div>
<!-- End #comment-list -->
EOD;
    
    if (!empty($id)) { // When Permalink
        $comment = $comment_list;
    } else {
        $comment = $comment_link;
    }

    return $comment;
}


/**
 * Post comment link (Forum style)
 */
function post_comment_forum() 
{
    global $lang, $id, $row, $forum_table;
    $tsql = "SELECT `tid` FROM `{$forum_table}` WHERE `refer_id` = '" . $row['id'] . "'";
    $tres = mysql_query($tsql);
    if ($tres) {
        $trow = mysql_num_rows($tres);
        $trows = mysql_fetch_array($tres);
        if ($trow == 0) {
            $comment_title = $lang['post_com_title_1'] . htmlspecialchars(strip_tags($row['name'])) . $lang['post_com_title_2'];
            $link = '<a href="./forum/add.php?refer_id=' . $row['id'] . '" title="'.$comment_title.'">Discuss';
        } else {
            $comment_title = $lang['view_com_title_1'] . htmlspecialchars(strip_tags($row['name'])) . $lang['view_com_title_2'];
            $link = '<a href="./forum/topic.php?tid=' . $trows['0'] . '" title="'.$comment_title.'" class="status-on">';
            if ($trow <= 1) {
                $cstr = ' Comment';
            } else {
                $cstr = ' Comments';
            }
            $link .= $trow . $cstr; // . ' (' . $trow . ')';
        }
        $link .= '</a>';
    } else {
        $link = ' Comment : Off';
    }
    if (empty($id)) {
        $comments = $link;
    } else {
        $comments =<<<EOD
<!-- Begin #comment-list -->
<div id="comment-list">
<h4 id="comments">Comments in Forum</h4>
<p class="ref">
{$link}
</p>
</div>
<!-- End #comment-list -->
EOD;
    }
    return $comments;
}




// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . "/index.php");
}
?>