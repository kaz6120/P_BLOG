<?php

/**
 * RSS Folder
 *
 * $Id: rss_folder.php, 2004/10/14 00:38:47 Exp $
 */

require_once './lib/php/XML/RSS.php';

// (1) データベースからr_uriを全て配列で取り出す
// (2) r_uriがある分だけ、$rss->parse()をループ。
// (3) getItems as $item で、<dc:date>の値を取り出す。
// (4) <dc:date>の値を、r_mod に置き換える。
// (5) データベースの「r_mod」を更新。


// リフレッシュ！
$contents =<<<RSS_LIST
<div class="section">
<h2 class="archive-title">RSS BOX</h2>
<ul class="rss-list">
RSS_LIST;

if (isset($_POST['refresh'])) {
    $sql = 'SELECT `r_uri` FROM `p_rss_box`';
    $res  = mysql_query($sql);
    while ($row = mysql_fetch_array($res)) {
        $rss =& new XML_RSS($row['r_uri']);
        $rss->parse();
        
        // getItem()でitemの配列の最初のitemの「dc:date」値を取出し、更新SQLにかけ、データベースの「r_mod」に入れる
        // 「dc:date」値が無いRSSは「pubdate」値を取り出す
        $item = $rss->getItems();
        if (isset($item[0]['dc:date'])) {
            $value  =  $item[0]['dc:date']; 
            $up_sql = "UPDATE p_rss_box SET r_mod='" . $value . "' WHERE r_uri='" . $row['r_uri'] . "'";
            mysql_query($up_sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
        } elseif (isset($item[0]['pubdate'])) {
            //$value =  date("Y-m-d H:i:s", $item[0]['pubDate']);
            $value  = strtotime($item[0]['pubdate']);
            $value  = date('Y-m-d H:i:s', $value); 
            $up_sql = "UPDATE p_rss_box SET r_mod='" . $value . "' WHERE r_uri='" . $row['r_uri'] . "'";
            mysql_query($up_sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
        }
        
    }
}

if (isset($_GET['rss_uri'])) {
    // Get URI request
    $feed = addslashes($_GET['rss_uri']);
    $rss  =& new XML_RSS($feed);
    $rss->parse();
    // Build SQL
    $sql = 'SELECT * FROM `p_rss_box` ORDER BY `r_mod` desc';
    $res  = mysql_query($sql);
    while ($row = mysql_fetch_array($res)) {
        if ($row['r_uri'] == $_GET['rss_uri']) {
            $contents .='<li class="open">'.
                 '<a href="./index.php?id=rss_box">'.
                 '<img src="./contents/rss_box_imgs/darr.png" width="15" height="15" alt="&darr;" />'.
                 '</a>'.
                 '&nbsp;&nbsp;<a href="'.$row['r_uri'].'">' . $row['r_name'] . '</a>' .
                 ' <span class="rss-date">(' . mb_strimwidth($row['r_mod'], 0, 10) . ')</span>'.
                 "</li>\n<ul>\n";
            
           foreach ($rss->getItems() as $item) {
                $contents .= '  <li class="no-img">'."\n";
                if (isset($item['dc:date'])) {
                    $contents .= '    <span class="rss-date">' . mb_strimwidth($item['dc:date'], 0, 16) . '</span> - ';
                }
                if ((!empty($item['link'])) && (!empty($item['title']))) {
                    $contents .= '<a href="' . $item['link'] . '">' . $item['title'] . "</a></li>\n";
                } elseif ((empty($item['link'])) && (!empty($item['title']))) {
                    $contents .= '<a href="' . $row['r_uri'] . '">' . $item['title'] . "</a></li>\n";
                } else {
                    $contents .= '<a href="' . $item['link'] . '">' . $item['link'] . "</a></li>\n";
                }
                
            }

            $contents .= "</ul>\n";
        } else {
            $contents .= "\n".
                 '  <li class="close">'."\n".
                 '    <a href="./index.php?id=rss_box&amp;rss_uri='.$row['r_uri'].'">'."\n".
                 '      <img src="./contents/rss_box_imgs/rarr.png" width="15" height="15" alt="&rarr;" />'."\n".
                 '    </a>&nbsp;&nbsp;' . $row['r_name'] . ' <span class="rss-date">(' . mb_strimwidth($row['r_mod'], 0, 10) . ')</span>'."\n".
                 '  </li>';
        }
        //echo "</li>\n";
    }
} else {
    $sql = 'SELECT * FROM `p_rss_box` ORDER BY `r_mod` desc';
    $res  = mysql_query($sql) or die("<h2>DB Not found</h2>");
    while ($row = mysql_fetch_array($res)) {
        $contents .= "\n".
             '  <li class="close">'."\n".
             '    <a href="./index.php?id=rss_box&amp;rss_uri='.$row['r_uri'].'">'."\n".
             '      <img src="./contents/rss_box_imgs/rarr.png" width="15" height="15" alt="&rarr;" />'."\n".
             '    </a>&nbsp;&nbsp;' . $row['r_name'] .
       //      '    <span class="rss-date">(' . mb_strimwidth($row['r_mod'], 0, 10) . ')</span>'."\n".
             '  </li>';
    }
}
$contents .=<<<RSS_LIST
</ul>
</div>
<form method="post" action="./index.php?id=rss_box">
<p>
<input type="submit" name="refresh" tabindex="1" accesskey="r" value="Refresh" />
</p>
</form>
RSS_LIST;
?>
