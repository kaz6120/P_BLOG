<?php
/**
 * RSS 2.0 Class
 *
 * @author  P_BLOG Project
 * @since   2005-11-28 09:59:34 
 * updated  2006-03-23 00:06:36
 */

// P_BLOG RSS Class
class P_BLOG_RSS {

    // Get Items
    function getItems($link, $title, $row)
    {
        global $cfg;

       $enclosures = $this->getEnclosure($row['comment']);
       $row['comment'] = $this->convertEnclosure($row['comment']);

        // Article Content
        $row['comment'] = str_replace(
            './resources/', 
            'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'resources/', 
            $row['comment']
        );

        // Description
        $description = htmlspecialchars(mb_substr(strip_tags($row['comment']), 0, 120, 'UTF-8')) . '...';

        return '<item>
<title>'. $title.'</title>
<link>'. $link .'</link>
<pubDate>'.date('D, d M Y H:i:s ', strtotime($row['date'])).tz().'</pubDate>
<description>' . $description . '</description>'.$enclosures.'
<content:encoded>
<![CDATA['. $row['comment'] . ']]>
</content:encoded>
</item>';

    }
    
    /**
     * Get Enclosure Information
     *
     * @param  string $enclosure_name
     * @return array  $item
     */
    function getEnclosureInfo($enclosure_name)
    {
        global $cd, $cfg;
        $filename = 'resources/'.$enclosure_name;
        $enclosure_path = $cd . '/' . $filename;
        if (file_exists($enclosure_path)) {
            $enclosure_name = $enclosure_name;
            $enclosure_size = filesize($enclosure_path);
            $enclosure_uri  = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $filename;
        } else {
            $enclosure_name = '';
            $enclosure_size = '';
            $enclosure_uri  = '';
        }
        $item['enclosure_name'] = $enclosure_name;
        $item['enclosure_size'] = $enclosure_size;
        $item['enclosure_uri']  = $enclosure_uri;
        return $item;
    }


    /**
     * Get Enclosure Node
     *
     * @param  string $enclosure_name
     * @return string
     */
    function getEnclosureNode($enclosure_name)
    {
        global $cd;
        $item = $this->getEnclosureInfo($enclosure_name);
        if (($item['enclosure_name'] != '') &&
            ($item['enclosure_size'] != '') &&
            ($item['enclosure_uri'] != '')) {
            $enclosure = '<enclosure '.
                         'url="'.$item['enclosure_uri'].'" '.
                         'length="'.$item['enclosure_size'].'" '.
                         'type="audio/mpeg" />'."\n";
        } else {
            $enclosure = '';
        }
        return $enclosure;
    }

    /**
     * Get Enclosure
     *
     * @param  string $comment
     * @return string $enclosure
     */
    function getEnclosure($comment)
    {
        global $cd;
        preg_match_all('/<!-- ?PODCAST=.*\.(mp3|m4a|m4v|mov|wav) ?-->/', $comment, $matches);
        $enclosure = '';
        for ($i = 0; $i < count($matches[0]); $i++) {
            $enclosure_name = substr($matches[0][$i], 13, strpos($matches[0][$i], ' -->')-13);
            $enclosure .= $this->getEnclosureNode($enclosure_name);
        }
        return $enclosure;
    }


    /**
     * Convert Enclosure
     *
     * @param  string $comment
     * @return string $comment
     */
    function convertEnclosure($comment)
    {
        global $cd;
        preg_match_all('/<!-- ?PODCAST=.*\.(mp3|m4a|m4v|mov|wav) ?-->/', $comment, $matches);
        $enclosure = '';
        for ($i = 0; $i < count($matches[0]); $i++) {
            $enclosure_name = substr($matches[0][$i], 13, strpos($matches[0][$i], ' -->')-13);
            $item = $this->getEnclosureInfo($enclosure_name);
            if (($item['enclosure_name'] != '') &&
                ($item['enclosure_size'] != '') &&
                ($item['enclosure_uri'] != '')) {
                $item['enclosure_size'] = toMB($item['enclosure_size']);
                if (stristr($item['enclosure_name'], '.mov')) {
                    $item['enclosure_class'] = 'mov';
                    $item['enclosure_mime']  = 'video/quicktime';
                } elseif (stristr($item['enclosure_name'], '.m4')) {
                    $item['enclosure_class'] = 'm4';
                    $item['enclosure_mime']  = 'audio/mpeg';
                } elseif (stristr($item['enclosure_name'], '.wav')) {
                    $item['enclosure_class'] = 'wav';
                    $item['enclosure_mime']  = 'audio/mpeg';
                } else {
                    $item['enclosure_class'] = 'mp3';
                    $item['enclosure_mime']  = 'audio/mpeg';
                }
                $enclosure_text = '<div class="podcast '.$item['enclosure_class'].'">
<p><strong><a href="'.$item['enclosure_uri'].'">'.$item['enclosure_name'].'</a></strong></p>
<p>( '.$item['enclosure_mime'].' : '. $item['enclosure_size'].' )</p>
</div>';
                $comment = preg_replace('/' .$matches[0][$i].'/', $enclosure_text, $comment);
            }
        }
        return $comment;
    }
    

    /**
     * Feed RSS
     */
    function feedRSS2()
    {
        global $cfg, $id, $tid, $row, $row2, $items, $item,
               $tz, $log_table, $f_index, $forum_table;

        if (isset($tid)) {
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] .
                    'forum/topic.php?tid=' . $tid . '&amp;p=0';
            $topic_title = ' - Forum Topic No:' . $tid;
            $d_sql = "SELECT DATE_FORMAT(`date`, '%Y-%m-%dT%T') as `date` ".
                     "FROM `{$forum_table}` ORDER BY `date` desc LIMIT 1";
            $d_res = mysql_query($d_sql);
            $d_row = mysql_fetch_array($d_res);
            $date = date('D, d M Y H:i:s ', strtotime($d_row['date'])).$tz;
        } elseif (isset($id)) {
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $cfg['top_page'];
            $topic_title = '';
            $date = date('D, d M Y H:i:s ', strtotime($row['date'])).$tz;
        } elseif (isset($f_index)) {
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/index.php';
            $topic_title = ' - Forum Index';
            $d_sql = "SELECT DATE_FORMAT(`date`, '%Y-%m-%dT%T') as `date` ".
                     "FROM `{$forum_table}` WHERE `parent_key` = 1 ORDER BY `date` DESC LIMIT 1";
            $d_res = mysql_query($d_sql);
            $d_row = mysql_fetch_array($d_res);
            $date  = date('D, d M Y H:i:s ', strtotime($d_row['date'])).$tz;
        } else {
            $link = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $cfg['top_page'];
            $topic_title = '';
            $d_sql = "SELECT DATE_FORMAT(`date`, '%Y-%m-%dT%T') as `date` ".
                     "FROM `{$log_table}` ".
                     "WHERE `draft` = '0' ORDER BY `date` desc LIMIT 1";
            $d_res = mysql_query($d_sql);
            $d_row = mysql_fetch_array($d_res);
            $date = date('D, d M Y H:i:s ', strtotime($d_row['date'])).$tz;
        }
    
        $blog_title = $cfg['blog_title'];
    
        header("Content-type: application/xml");  
        echo <<<RSS_FEED
<?xml version="1.0" encoding="{$cfg['charset']}"?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
<title>{$cfg['blog_title']}{$topic_title}</title>
<link>{$link}</link>
<pubDate>{$date}</pubDate>
<description>
{$cfg['blog_title']} - RSS 2.0 (Really Simple Syndication).
</description>
{$item}
</channel>
</rss>
RSS_FEED;

    }
}

?>
