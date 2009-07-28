<?php
/**
 * $Id: 2005/02/01 15:13:41 Exp $
 */

if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}


function msg_ja() {
    $msg['lang_anchor']    = '<a href="' . $_SERVER['PHP_SELF'] . '?ex-lang=en"> English ';
    $msg['title']          = 'P_BLOGへようこそ';
    $msg['define_p_blog']  = 'P_BLOGはPHP+MySQLベースのWeblogシステムです。'.
                             '通常のログ管理機能の他、ファイルアップローダー、アクセス解析、'.
                             'コメント／フォーラム機能、トラックバック送受信機能、更新Ping送信機能、そして独自のコンテンツ拡張機能「Vars」等があります。'.
                             'P_BLOGは<abbr title="World Wide Web Consortium">W3C</abbr>勧告に完全準拠したXHTML1.0 Strict 又は XHTML1.1を出力します。';
    $msg['license']        = 'ライセンス';
    $msg['license_exp']    = 'P_BLOGは<abbr title="GNU Public License">GPL</abbr>に準拠したオープンソースの<abbr title="「フリー」の定義は「自由」で、価格の問題ではありません。">フリーソフトウェア</abbr>です。';
    $msg['release']        = '最近のベータリリース';
    $msg['released']       = 'をリリースしました。';
    $msg['whats_next_msg'] = '<del>2004年9月</del> <span class="important"><ins>2004年10月</ins> 中にver.1.0<abbr title="Release Candidate / 正式リリース版候補">RC</abbr>1をリリース予定です。</span>';
    $msg['requirements']   = '必要環境';
    $msg['os_independent'] = 'OSに依存しません。';
    return $msg;
}
function msg_en() {
    $msg['lang_anchor']    = '<a href="' . $_SERVER['PHP_SELF'] . '?ex-lang=ja"> 日本語 ';
    $msg['title']          = 'Welcome to P_BLOG';
    $msg['define_p_blog']  = 'P_BLOG is a PHP and MySQL driven Weblog sytem '.
                             'with binary file uploader, access analyzer, '.
                             'Comment / Forum System, Trackback and Update Ping sending, and extensible yet simple content management function "Vars".<br />'.
                             'P_BLOG generates <abbr title="World Wide Web Consortium">W3C</abbr>-Valid XHTML1.0 Strict or XHTML1.1.';
    $msg['license']        = 'License';
    $msg['license_exp']    = 'P_BLOG is an open sourced <abbr title="&quot;Free software&quot; is a matter of liberty, not price.">Free Software</abbr> and is being developed under <abbr title="GNU Public License">GPL</abbr>.';
    $msg['release']        = 'Recent Beta Releases';
    $msg['released']       = 'has been released.';
    $msg['whats_next_msg'] = '<span class="important">Ver.1.0<abbr title="Release Candidate">RC</abbr>1 will be available on </span><del>September 2004</del> <span class="important"> <ins>October 2004</ins>.</span>';
    $msg['requirements']   = 'Requirements';
    $msg['os_independent'] = 'OS Independent.';
    return $msg;
}

if (strstr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'ja')) {
    if ((!empty($_GET['ex-lang'])) && ($_GET['ex-lang'] == 'en')) {
        $msg = msg_en();
    } else {
        $msg = msg_ja();
    }
} else {
    if ((!empty($_GET['ex-lang'])) && ($_GET['ex-lang'] == 'ja')) {
        $msg = msg_ja();
    } else {
        $msg = msg_en();
    }
}

$section_content =<<<EOD
<!-- Announcement -->
<div id="announce">
<h2>{$msg['title']}</h2>
<div id="switch-lang">({$msg['lang_anchor']}</a>)</div>
<img src="./images/p_blog_icon.png" class="p_blog-icon" width="128" height="128" alt="P_BLOG Icon" />
<p>{$msg['define_p_blog']}</p>
<h3>{$msg['license']}</h3>
<p>
<a href="http://gnu.fyxm.net/copyleft/gpl.html" title="GNU General Public License - GNU Project - Free Software Foundation (FSF)">
<img src="./images/gpl_fsf.png" width="88" height="31" class="logo" alt="GPL - Free Software Foundation" /> 
</a>
{$msg['license_exp']}</p>
<h3>{$msg['requirements']}</h3>
<p>
<a href="http://www.php.net">
<img src="./images/php-power-micro.png" class="mini-logo" width="80" height="15" alt="PHP" />
</a>
<a href="http://www.mysql.com">
<img src="./images/mysql_logo.png" class="mini-logo" width="80" height="15" alt="MySQL" />
</a>
<img src="./images/os_independent.png" width="149" height="33" title="OS Independent" alt="OS Independent" />
{$msg['os_independent']}
</p>
</div>
<!-- End of Announcement -->
EOD;
?>