<?php
/**
 * @author: P_BLOG Project
 * $Id: 2005/02/02 21:25:42 Exp $
 */

if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}

// Switch Language
if (isset($_GET['ex-lang']) == 'ja') {
    $lang['p_blog_manual'] = 'P_BLOG マニュアル';
    $lang_anchor    = '"> English ';
    $lang['choose_lang']    = '言語を選択して下さい';
    $lang['japanese'] = '日本語';
    $lang['english']  = '英語';
} else {
    $lang['p_blog_manual']          = 'P_BLOG Manual';
    $lang_anchor    = '?ex-lang=ja"> 日本語 ';
    $lang['choose_lang'] = 'Choose Your Language';
    $lang['japanese'] = 'JAPANESE';
    $lang['english']  = 'ENGLISH';
}

$p_blog_version = P_BLOG_VERSION;

$contents =<<<EOD
<div class="section">
<div id="announce">
<h2>{$lang['p_blog_manual']} ver.{$p_blog_version}</h2>
<p>
<img src="../../../images/p_blog_icon.png" class="p_blog-icon" width="128" height="128" alt="P_BLOG" />
</p>
<p id="switch-lang">(<a href="{$_SERVER['PHP_SELF']}{$lang_anchor}</a>)</p>
<h3>{$lang['choose_lang']}</h3>
<ul class="flip-menu">
<li><a href="index.php?id=en_00" accesskey="e">{$lang['english']}</a></li>
<li><a href="index.php?id=ja_00" accesskey="j">{$lang['japanese']}</a></li>
</ul>
<address>
Copyright (c) 2005 P_BLOG Project. All Rights Reserved.
</address>
</div><!-- End .announce -->
</div><!-- End .section -->
EOD;

?>