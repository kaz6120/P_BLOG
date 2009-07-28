<?php
/**
 * ANTENNA INCLUDER
 *
 * @author   : かきたろう / なまがき日記<http://namagaki.net/weblog/index.php>
 * @modified : kaz
 *
 * $Id: antenna.php, 2005/01/24 21:02:34 Exp $
 */

//////////////////////////////////////////////////////////////////////////
// NOTE:
//  This script can load the content of the Antenna site to your P_BLOG.
// 
// How to:
//   access to "/var/index.php?id=antenna". 
//
// ----------------------------------------------------------------------------------------------------------------------------------------------
// 説明：
//  アンテナサイトのコンテンツ部をP_BLOG内に読み込むスクリプトです。
// 
// 使い方：
//  /var/index.php?id=antennaとして読み込みます。
//
/////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////
// 設定ここから


// アンテナのURIを設定
$uri = '/directly/to/anntena/file/index.html';

// 削除したくないタグ群
$tags = "<p><ol><li><span><a><br><hr><h3>";


// 設定ここまで
/////////////////////////////////////////////////////////////////////////


//アンテナファイルを読み込み
$anntena = @file_get_contents($uri);

// index.htmlが空だった場合は何もしないで抜ける
if (empty($anntena)) {
    return;
} else {
    //EUC-JPからUTF-8へコンバート
    $anntenaHTML = trim(mb_convert_encoding($anntena, "UTF-8", "EUC-JP"));
    
    //XHTML出力（必要なタグのみ残して不必要なタグは削除）
    $contents = strip_tags($anntenaHTML, $tags);
}
?>