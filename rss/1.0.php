<?php
/**
 * RSS-1.0 generator
 *
 * @author: P_BLOG Project
 * $Id: 1.0.php, 2005-11-24 11:10:30 Exp $
 */
 
$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once './include/fnc_rss.inc.php';

// XML Header
function rss_feed()
{
    global $cfg, $id, $tid, $row, $row2, $items, $item, $rdf_about_uri, $tz, $f_index, $forum_table;

    if (isset($tid)) {
        $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/topic.php?tid=' . $tid . '&amp;p=0';
        $topic_title = ' - Forum Topic No:' . $tid;
        $d_sql = "SELECT DATE_FORMAT(`date`, '%Y-%m-%dT%T') as `date` ".
                 "FROM `{$forum_table}` ORDER BY `date` desc LIMIT 1";
        $d_res = mysql_query($d_sql);
        $d_row = mysql_fetch_array($d_res);
        $date = $d_row['date'];
    } elseif (isset($id)) {
        $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $cfg['top_page'];
        $topic_title = '';
        $date = $row['date'];
    } elseif (isset($f_index)) {
        $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/index.php';
        $topic_title = ' - Forum Index';
        $d_sql = "SELECT DATE_FORMAT(`date`, '%Y-%m-%dT%T') as `date` ".
                 "FROM `{$forum_table}` ".
                 "WHERE `parent_key` = 1 ORDER BY `date` DESC LIMIT 1";
        $d_res = mysql_query($d_sql);
        $d_row = mysql_fetch_array($d_res);
        $date  = $d_row['date']; 
    }
    
    $blog_title = $cfg['blog_title'];
    
    header("Content-type: application/xml");  
    echo <<<RSS_FEED
<?xml version="1.0" encoding="{$cfg['charset']}"?>
<?xml-stylesheet href="http://{$_SERVER['HTTP_HOST']}{$cfg['root_path']}rss/style.css" type="text/css"?>
<rdf:RDF xmlns="http://purl.org/rss/1.0/"
         xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
         xmlns:content="http://purl.org/rss/1.0/modules/content/"
         xmlns:dc="http://purl.org/dc/elements/1.1/"
         xml:lang="{$cfg['xml_lang']}">
<channel rdf:about="{$rdf_about_uri}">
<title>{$cfg['blog_title']}{$topic_title}</title>
<link>{$link}</link>
<dc:date>{$date}{$tz}</dc:date>
<description>
{$cfg['blog_title']} - RSS (RDF Site Summary).
</description>
<items>
<rdf:Seq>
{$items}</rdf:Seq>
</items>
</channel>
{$item}
</rdf:RDF>
RSS_FEED;

}

if (!mysql_query(isset($sql))) {
    // Query to pull out the recent articles.
    // NOTE: to generate valid RSS, set the date format to 'ISO 8601'.
    // See 'http://www.w3.org/TR/NOTE-datetime' for more info about 'Date and Time formats'. 
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = 'SELECT'.
               " `id`, `href`, `name`, DATE_FORMAT(`date`,'%Y-%m-%dT%T') as `date`, `comment`, `category`".
               " FROM `{$log_table}` WHERE (`draft` = '0') AND (`id` = '{$id}')";
        $res = mysql_query($sql);
        $items = '';
        $item  = '';
        if ($row = mysql_fetch_array($res)) {
            
            $row = convert_to_utf8($row);
            
            $rdf_about_uri = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'rss/1.0.php?id=' . $row['id'];
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'article.php?id=' . $row['id'];
            // Generate "rdf:li" list in <items>
            $items .= '<rdf:li rdf:resource="'.$link.'" />'."\n";
            
            // Generate item
            $item .= "<item>\n".
                      '<title>'. htmlspecialchars($row['name']) ."</title>\n".
                      '<link>' . $link ."</link>\n";  
            // Just replace "<foo>" tag code into &lt;foo&gt;
            // -- this looks better in NetNewsWire RSS Viewer.
            $row['comment'] = str_replace(
                './resources/', 
                'http://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].'resources/', 
                $row['comment']
            );
            
            // Convert Text to XHTML
            if (file_exists($cd . '/include/user_include/plugins/plg_markdown.inc.php')) {
                include_once $cd . '/include/user_include/plugins/plg_markdown.inc.php';
                $FKMM_markdown = new FKMM_markdown();
                $row['comment'] = $FKMM_markdown->convert($row['comment']);
            }
            // Trim "comment" data for description
            $description     = htmlspecialchars(mb_substr(strip_tags($row['comment']), 0, 120, 'UTF-8')) . '...';        
            // This is for "content module"
            $content_encoded = '<![CDATA['."\n". $row['comment'] . "\n".']]>';
            //$content_encoded = htmlspecialchars($row['comment']);
            
            $tz = tz();
            $item .= '<dc:date>' . $row['date'] . $tz . "</dc:date>\n".
                     '<description>' . $description . "</description>\n".
                     '<content:encoded>'  . "\n" .
                     $content_encoded     . "\n" .
                     '</content:encoded>' . "\n" .
                     "</item>\n";
        }
        rss_feed();
    } elseif (isset($_GET['tid'])) { // RDF for forum topics
        // get topic thread
        $tid = intval($_GET['tid']);
        $sql = 'SELECT'.
               " `id`, `tid`, `title`, DATE_FORMAT(`date`, '%Y-%m-%dT%T') as `date`, `comment`".
               " FROM `{$forum_table}` WHERE (`trash` = '0') AND (`tid` = '{$tid}')";
        $res = mysql_query($sql);
        
        // get title of the topic
        $sql2 = 'SELECT ' .
                "`title` FROM `{$forum_table}` WHERE (`tid` = '" . $tid ."') AND (`parent_key` = 1)";
        $res2 = mysql_query($sql2);
        $row2 = mysql_fetch_array($res2);
        
        $rdf_about_uri = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'rss/1.0.php?tid=' . $tid;
        $items = '';
        $item  = '';
        while ($row = mysql_fetch_array($res)) {
            
            $row = convert_to_utf8($row);
            
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 
                    'forum/reply.php?tid=' . $row['tid'] . '&amp;qid=' . $row['id'];
            // Generate "rdf:li" list in <items>
            $items .= '<rdf:li rdf:resource="'.$link.'" />'."\n";
            
            $item .= "<item>\n".
                     '<title>'. htmlspecialchars($row['title']) ."</title>\n".
                     '<link>' . $link ."</link>\n";  
            // Just replace "<foo>" tag code into &lt;foo&gt;
            // -- this looks better in NetNewsWire RSS Viewer.
            $row['comment'] = str_replace("./resources/", 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'resources/', $row['comment']);

            // Trim "comment" data for description
            $description     = htmlspecialchars(mb_substr(strip_tags($row['comment']), 0, 125, 'UTF-8')) . '...';        
            // This is for "content module"
            //$row['comment'] = smiley($row['comment']);
            $content_encoded = '<![CDATA['."\n". nl2p(htmlspecialchars($row['comment'])) . "\n".']]>';
            
            $tz = tz();
            $item .= '<dc:date>' . $row['date'] . $tz . "</dc:date>\n".
                     '<description>' . $description . "</description>\n".
                     '<content:encoded>'  . "\n" .
                     $content_encoded     . "\n" .
                     '</content:encoded>' . "\n" .
                     "</item>\n";
        }
        rss_feed();
    } elseif (isset($_GET['f_index'])) { // RDF for forum index
        $f_index = $_GET['f_index'];
        // get topic index
        $sql = 'SELECT'.
               " `id`, `tid`, `title`, DATE_FORMAT(`date`, '%Y-%m-%dT%T') as `date`, `comment`".
               " FROM `{$forum_table}` WHERE `parent_key` = 1 ORDER BY `mod` DESC LIMIT " . $cfg['topic_max'] ;
        $res = mysql_query($sql);
        $rdf_about_uri = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'rss/1.0.php?f_index';
        $items = '';
        $item  = '';
        while ($row = mysql_fetch_array($res)) {
        
            // Convert retrieved data into UTF-8.
            $row = convert_to_utf8($row);
            
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/topic.php?tid=' . $row['tid'] . '&amp;p=0';
            $items .= '<rdf:li rdf:resource="'.$link.'" />'."\n";
            $item  .= "<item>\n".
                      '<title>'. htmlspecialchars($row['title']) ."</title>\n".
                      '<link>' . $link ."</link>\n";  
            // Just replace "<foo>" tag code into &lt;foo&gt;
            // -- this looks better in NetNewsWire RSS Viewer.
            $row['comment'] = str_replace("./resources/", 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'resources/', $row['comment']);
        
            // Trim "comment" data for description
            $description     = htmlspecialchars(mb_substr(strip_tags($row['comment']), 0, 125, 'UTF-8')) . '...';
 
            // This is for "content module"
            $content_encoded = '<![CDATA['."\n". nl2p(htmlspecialchars($row['comment'])) . "\n".']]>';
            //$content_encoded = htmlspecialchars($row['comment']);
            
            $tz = tz();
            $item .= '<dc:date>' . $row['date'] . $tz . "</dc:date>\n".
                     '<description>' . $description . "</description>\n".
                     '<content:encoded>'  . "\n" .
                     $content_encoded     . "\n" .
                     '</content:encoded>' . "\n" .
                     "</item>\n";
        }
        rss_feed();
    }
}
?>
