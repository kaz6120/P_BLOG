<?php
//---------------------------------------------------------------------------------------------------
//
//        markdown変換プラグイン
//
//---------------------------------------------------------------------------------------------------
//
//    ■変更履歴
//        2005-04-05    markdown変換プラグイン ver 0.01
//            ・ログをmarkdownでXHTMLへ変換します。
//
//    ■免責
//        スクリプトの利用は自己責任でお願いします。作者は一切の責任を負いません。
//
//    ■概要
//        include/fnc_logs.inc.php
//        files/include/fnc_files.inc.php
//        にHack用のコードを挿入することで、ログをmarkdownで変換できるようになります。
//
//    ■設置方法
//        １．plg_markdown.inc.phpは、include/user_include/pluginsフォルダに入れてください。
//        ２．残りのファイルは、include/user_include/plugins/plg_markdown_includeフォルダを
//            作成してその中に入れてください。
//        ３．次に以下のファイルをHackします。
//            ★まずinclude/fnc_logs.inc.phpの189行目あたりfunction display_article_box($row) 内の、
//                // Convert Text to XHTML
//            という記述の直前に
//            // add doublebass
//            if (file_exists($cd . '/include/user_include/plugins/plg_markdown.inc.php')) {
//                include_once $cd . '/include/user_include/plugins/plg_markdown.inc.php';
//                $FKMM_markdown = new FKMM_markdown();
//                $row['comment'] = $FKMM_markdown->convert($row['comment']);
//            }
//            // add end
//            を追加します。
//            ★次にfiles/include/fnc_files.inc.phpの139行目あたりfunction display_binary_box($row) 内の、
//                // Convert Text to XHTML
//            という記述の直前に
//            // add doublebass
//            if (file_exists($cd . '/include/user_include/plugins/plg_markdown.inc.php')) {
//                include_once $cd . '/include/user_include/plugins/plg_markdown.inc.php';
//                $FKMM_markdown = new FKMM_markdown();
//                $row['bincomment'] = $FKMM_markdown->convert($row['bincomment']);
//            }
//            // add end
//            を追加します。
//        ４．以上で設定完了です。
//            
//    ■使い方
//        ログを書く際にmarkdownで記述すると表示の際にXHTMLに変換して表示します。
//    ■サポート
//        ご意見、ご要望等あれば、福耳 Cafe掲示板までお気軽にご連絡ください。
//
//---------------------------------------------------------------------------------------------------

if (file_exists($cd . '/include/user_include/plugins/plg_markdown_include/markdown.php')) {
    require_once($cd . '/include/user_include/plugins/plg_markdown_include/markdown.php');
}


//----------------------------------------------------------------------------
//markdown変換クラス
class FKMM_markdown {
    /**
     * コメントをXHTMLに変換
     *
     * @param    $comment    (i)
     * @retval   変換後のコメント
     * @since    2005/04/05 doublebass updated: 2005-11-29 14:17:54 
     * @note
     */
    function convert($comment)
    {
        return str_replace(
            "><", ">\n<", str_replace(
                '<p><hr /></p>', '<hr />', str_replace(
                    "\n\n", "\n", Markdown($comment)
                )
            )
        );
    }
}

// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])."/index.php");
}
?>