<?php
// Last modified: $Date: 2003-11-12T02:35:27+09:00 $

require_once './lib/php/XML/RSS.php';
// require_once './contents/rss_links_list.inc.php';

// (1) データベースからr_uriを全て配列で取り出す
// (2) r_uriがある分だけ、$rss->parse()をループ。
// (3) getItems as $item で、<dc:date>の値を取り出す。
// (4) <dc:date>の値を、r_mod に置き換える。
// (5) データベースの「r_mod」を更新。

$sql = 'SELECT `r_uri` FROM `p_rss_folder`';
$res  = mysql_query($sql);
while ($row = mysql_fetch_array($res)) {
    $rss =& new XML_RSS($row['r_uri']);
    $rss->parse();

    foreach($rss->getChannelInfo() as $key => $value) {
        //if ($key == 'dc:date') { echo $key . ' : ' . $value . '<br />'; }
        if ($key == 'dc:date') {
            $up_sql = "UPDATE p_rss_folder SET r_mod='" . $value . "' WHERE r_uri='" . $row['r_uri'] . "'";
            mysql_query($up_sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
        } elseif ($key == 'pubDate') {
            $fvalue = strtotime($value);
            $fdate  = date('Y-m-d H:i:s', $fvalue);
            $up_sql = "UPDATE p_rss_folder SET r_mod='" . $fdate . "' WHERE r_uri='" . $row['r_uri'] . "'";
            mysql_query($up_sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());        
        }

    }
}

?>
