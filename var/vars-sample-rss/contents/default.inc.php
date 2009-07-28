<?php
/**
 * VARS default include file sample
 *
 * $Id: var/vars-sample-rss/contents/default.inc.php, 2004/10/14 00:24:37 Exp $
 */
if (stristr($_SERVER['PHP_SELF'], ".inc.php")){
	die("Hello, World! This is an include file.");
}

// RSS 2 XHTML
require_once './lib/php/XML/RSS.php';

if ((isset($_GET['rss_uri'])) && ($_GET['rss_uri'] != '')) {
//    $feed = addslashes(strip_tags($_GET['rss_uri']));
    $feed = strip_tags($_GET['rss_uri']);
    $rss  =& new XML_RSS($feed);
    $rss->parse();
    
    $parse = '<blockquote>';
    foreach($rss->getItems() as $item) {
           $parse .= '<a href="' . $item['link'] . '">' . $item['title'] . '</a> - ' . $item['dc:date'] .
                     '<p>' . $item['description'] . "</p>\n<hr />\n";      
    }
    $parse .= '</blockquote>';

} else {
    $parse = "<p>Status : idle...</p>\n";
}

$contents =<<<EOD
<div class="section">
<h2>RSS 2 XHTML</h2>
<div class="section">
<h3>Convert RSS to XHTML</h3>
<form action="./index.php" method="get">
<p>
<label for="rss-uri">RSS URI : </label>
<input id="rss-uri" type="text" accesskey="u" tabindex="2" name="rss_uri" size="30" value="" />
<input type="submit" value="Parse!" />
</p>
</form>
</div>
<hr />
{$parse}
<div class="section">
<h2>RSS BOX</h2>
<p>→ <a href="./index.php?id=rss_box">RSS Box</a></p>
</div>
</div>
EOD;

?>
