<?php
/**
 * RSS-1.0 generator
 *
 * $Id: rss/recent.php, 2005-11-24 11:10:40 Exp $
 */
 
$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once './include/fnc_rss.inc.php';

if (!mysql_query(isset($sql))) {
    // Query to pull out the recent articles.
    // NOTE: to generate valid RSS, set the date format to 'ISO 8601'.
    // See 'http://www.w3.org/TR/NOTE-datetime' for more info about 'Date and Time formats'. 
    $sql = 'SELECT'.
           " `id`, `href`, `name`, DATE_FORMAT(`date`,'%Y-%m-%dT%T') as `date`, `comment`, `category`".
           " FROM `{$log_table}` WHERE `draft` = '0' ORDER BY `date` desc LIMIT {$cfg['pagemax']}";
    $res = mysql_query($sql);
    $items = '';
    $item  = '';
    while ($row = mysql_fetch_array($res)) {
        // Convert retrieved data into UTF-8.
        $row = convert_to_utf8($row);
        $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'article.php?id=' . $row['id'];
        
        // Generate "rdf:li" list in <items>
        $items .= '<rdf:li rdf:resource="'.$link.'" />'."\n";
        
        // Generate each <item>
        $item .= '<item rdf:about="' . $link . '">'."\n".
                 '<title>'. htmlspecialchars($row['name']) ."</title>\n".
                 '<link>' . $link ."</link>\n";  
        // Just replace "<foo>" tag code into &lt;foo&gt;
        // -- this looks better in NetNewsWire RSS Viewer.
        $row['comment']  = str_replace("./resources/", 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'resources/', $row['comment']);

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

        $tz = tz();
        $item .= '<dc:date>' . $row['date'] . $tz . "</dc:date>\n".
                 '<description>' . $description . "</description>\n".
                 '<content:encoded>'  . "\n" .
                 $content_encoded     . "\n" .
                 '</content:encoded>' . "\n" .
                 "</item>\n";
    }
}

// Generate Date SQL
$tz = tz();
if (!mysql_query(isset($d_sql))) {
    $d_sql = "SELECT DATE_FORMAT(`date`, '%Y-%m-%dT%T') as `date` ".
             "FROM `{$log_table}` ".
             "WHERE `draft` = '0' ORDER BY `date` desc LIMIT 1";
    $d_res = mysql_query($d_sql);
    $row = mysql_fetch_array($d_res);
}

// Send XML Header
header("Content-type: application/xml");
//header("Content-type: application/rdf+xml");
echo <<<RSS_FEED
<?xml version="1.0" encoding="{$cfg['charset']}"?>
<?xml-stylesheet href="http://{$_SERVER['HTTP_HOST']}{$cfg['root_path']}rss/style.css" type="text/css"?>
<rdf:RDF xmlns="http://purl.org/rss/1.0/"
         xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
         xmlns:content="http://purl.org/rss/1.0/modules/content/"
         xmlns:dc="http://purl.org/dc/elements/1.1/"
         xml:lang="{$cfg['xml_lang']}">
<channel rdf:about="http://{$_SERVER['HTTP_HOST']}{$cfg['root_path']}rss/recent.php">
<title>{$cfg['blog_title']}</title>
<link>http://{$_SERVER['HTTP_HOST']}{$cfg['root_path']}{$cfg['top_page']}</link>
<dc:date>{$row['date']}{$tz}</dc:date>
<description>
{$cfg['blog_title']} - RSS (RDF Site Summary) Feed.
</description>
<items>
<rdf:Seq>
{$items}</rdf:Seq>
</items>
</channel>
{$item}
</rdf:RDF>
RSS_FEED;
?>
