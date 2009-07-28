<?php
/**
 * Block Trackback Plugin
 *
 * 記事内に言及URIのないトラックバックをブロックするプラグイン
 *
 * @author    nakamuxu
 * @author    kaz
 * @since     2006-08-11 23:15:26
 * updated    2006-08-12 14:41:55
 */

class P_BLOG_TrackbackSpamBlocker {

    function denyTrackbackWithoutRef($articleId) 
    {
        global $cfg, $url, $http;

        $site_url = $http.'://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].'article.php?id='.urlencode($id);
        if (preg_match('/\/\/www\./', $site_url)) {
            $site_url_2 = preg_replace('/\/\/www\./', "//", $site_url);
        }
        $site_url_3 = preg_replace('/~/', "%7E", $site_url);
        $site_url_4 = preg_replace('/~/', "%7E", $site_url_2);
        $ping_url = trim($url, "\\\"");
        $html_content = @file_get_contents($ping_url); // do not report errors !
        $pos = stristr($html_content, $site_url);
        if (($pos === false) && (!empty($site_url_2))) {
            $pos = stristr($html_content, $site_url_2);
        }
        if (($pos === false) && (!empty($site_url_3))) {
            $pos = stristr($html_content, $site_url_3);
        }
        if (($pos === false) && (!empty($site_url_4))) {
            $pos = stristr($html_content, $site_url_4);
        }    
        if ($pos === false) {
            echo '<?xml version="1.0" encoding="UTF-8"?>
<response>
<error>1</error>
<message>Ping denied.</message>
</response>';
            exit;
        }
    }
}
?>