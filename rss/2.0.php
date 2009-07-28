<?php
/**
 * RSS-2.0 generator
 *
 * @author  P_BLOG Project
 * @since   2005-11-28 09:59:34 updated: 2005-12-16 10:37:56
 */
 
$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once './include/fnc_rss.inc.php';
require_once './include/P_BLOG_RSS.class.php';

$p_rss = new P_BLOG_RSS;

if (!mysql_query(isset($sql))) {
    // Permalink
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = 'SELECT'.
               " `id`, `href`, `name`,".
               " DATE_FORMAT(`date`,'%Y-%m-%dT%T') as `date`, `comment`, `category`".
               " FROM `{$log_table}` WHERE (`draft` = '0') AND (`id` = '{$id}')";
        $res = mysql_query($sql);
        $item  = '';
        if ($row = mysql_fetch_array($res)) {
            $row = convert_to_utf8($row);
            $link = 'http://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].'article.php?id='.$row['id'];
            $title = htmlspecialchars($row['name']);
            if (file_exists($cd . '/include/user_include/plugins/plg_markdown.inc.php')) {
                include_once $cd . '/include/user_include/plugins/plg_markdown.inc.php';
                $FKMM_markdown = new FKMM_markdown();
                $row['comment'] = $FKMM_markdown->convert($row['comment']);
            }
            $item .= $p_rss->getItems($link, $title, $row);
        }
        $p_rss->feedRSS2();
        
    // Forum Topics
    } elseif (isset($_GET['tid'])) {
        // Get Topic Thread
        $tid = intval($_GET['tid']);
        $sql = 'SELECT'.
               " `id`, `tid`, `title`, DATE_FORMAT(`date`, '%Y-%m-%dT%T') as `date`, `comment`".
               " FROM `{$forum_table}` WHERE (`trash` = '0') AND (`tid` = '{$tid}')";
        $res = mysql_query($sql);
        // Get Topic Title
        $sql2 = 'SELECT ' .
                "`title` FROM `{$forum_table}` WHERE (`tid` = '" . $tid ."') AND (`parent_key` = 1)";
        $res2 = mysql_query($sql2);
        $row2 = mysql_fetch_array($res2);
        $item  = '';
        while ($row = mysql_fetch_array($res)) {
            $row = convert_to_utf8($row);
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 
                    'forum/reply.php?tid=' . $row['tid'] . '&amp;qid=' . $row['id'];
            $title = htmlspecialchars($row['title']);
            $row['comment'] = nl2p(htmlspecialchars($row['comment']));
            $item .= $p_rss->getItems($link, $title, $row);
        }
        $p_rss->feedRSS2();
    
    // Forum Index
    } elseif (isset($_GET['f_index'])) {
        $f_index = $_GET['f_index'];
        $sql = 'SELECT'.
               " `id`, `tid`, `title`, DATE_FORMAT(`date`, '%Y-%m-%dT%T') as `date`, `comment`".
               " FROM `{$forum_table}`".
               " WHERE `parent_key` = 1 ORDER BY `mod` DESC LIMIT " . $cfg['topic_max'] ;
        $res = mysql_query($sql);
        $item  = '';
        while ($row = mysql_fetch_array($res)) {
            $row = convert_to_utf8($row);
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 
                    'forum/topic.php?tid=' . $row['tid'] . '&amp;p=0';
            $row['comment'] = nl2p(htmlspecialchars($row['comment']));
            $title = htmlspecialchars($row['title']);
            $item .= $p_rss->getItems($link, $title, $row);
        }
        $p_rss->feedRSS2();
    
    // Recent Articles
    } else {
        $sql = 'SELECT'.
               " `id`, `href`, `name`, DATE_FORMAT(`date`,'%Y-%m-%dT%T') as `date`, `comment`, `category`".
               " FROM `{$log_table}` WHERE `draft` = '0' ORDER BY `date` desc LIMIT {$cfg['pagemax']}";
        $res = mysql_query($sql);
        $item  = '';
        while ($row = mysql_fetch_array($res)) {
            $row = convert_to_utf8($row);
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'article.php?id=' . $row['id'];
            $title = htmlspecialchars($row['name']);
            if (file_exists($cd . '/include/user_include/plugins/plg_markdown.inc.php')) {
                include_once $cd . '/include/user_include/plugins/plg_markdown.inc.php';
                $FKMM_markdown = new FKMM_markdown();
                $row['comment'] = $FKMM_markdown->convert($row['comment']);
            }
            $item .= $p_rss->getItems($link, $title, $row);
        }
        $p_rss->feedRSS2();
    }
}
?>
