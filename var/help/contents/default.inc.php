<?php
/**
 * $Id: 2004/08/31 19:23:06 Exp $
 */
 
if ($cfg['xml_lang'] == 'ja') {
    $how_to_search       = '<a href="./index.php?id=how_to_search_ja"> ログの検索方法について</a>';
    $_lang['manual']     = 'マニュアル';
    $_lang['accesskeys'] = 'アクセスキー';
} else {
    $how_to_search       = '<a href="./index.php?id=how_to_search_en"> How to Search</a>';
    $_lang['manual']     = 'Manual';
    $_lang['accesskeys'] = 'Access Keys';
}

if (file_exists('./man')) {
    $man_path = '<li><a href="./man/index.php"> P_BLOG ' . $_lang['manual'] . '</a> (<a href="./man/index.php?id=en_00">English</a> / <a href="./man/index.php?id=ja_00">日本語</a>)</li>'."\n";
} else {
    $man_path = '';
}

$contents .=<<<EOD
<h2>{$lang['help']}</h2>
<ul class="ref">
<li>{$how_to_search}</li>
<li><a href="./index.php?id=accesskeys">{$_lang['accesskeys']}</a></li>
{$man_path}</ul>

EOD;
?>