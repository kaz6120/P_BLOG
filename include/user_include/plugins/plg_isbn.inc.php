<?php
//---------------------------------------------------------------------------------------------------
//
//        ISBN変換プラグイン
//
//---------------------------------------------------------------------------------------------------
//
//    ■変更履歴
//        2005-05-10    ISBN変換プラグイン ver 0.08
//            ・重複定義エラーが表示される場合があったので回避
//        2005-04-04    ISBN変換プラグイン ver 0.07
//            ・ワーニングがでていた箇所があったので修正
//        2005-03-28    ISBN変換プラグイン ver 0.06
//            ・タイトルやテキストに&などの特殊文字がある場合と
//                作者が無い場合にAnother HTML-lintでエラーになる件を修正
//        2005-03-22    ISBN変換プラグイン ver 0.05
//            ・タグが大文字になっていた箇所を小文字に修正、imgにalt属性追加
//        2005-03-21    ISBN変換プラグイン ver 0.04
//            ・typo修正、デバッグ用のコード挿入
//        2005-03-20    ISBN変換プラグイン ver 0.03
//            ・includeのパスの設定を変更
//        2005-03-18    ISBN変換プラグイン ver 0.02
//            ・表示を少し変更
//        2005-03-18    ISBN変換プラグイン ver 0.01
//            ・AMAZONのISBNを商品へのリンクに変換するプラグインを作成しました。
//
//    ■免責
//        スクリプトの利用は自己責任でお願いします。作者は一切の責任を負いません。
//
//    ■概要
//        include/fnc_logs.inc.php
//        にHack用のコードを挿入することで、各ログのISBNを商品へのリンクに変換することができるようになります。
//
//    ■設置方法
//        １．plg_isbn.inc.phpは、include/user_include/pluginsフォルダに入れてください。
//        ２．plg_isbn.inc.phpのアソシエイトIDとDevelop Tokenをご自分のものに変更してください。
//        ３．残りのファイルは、include/user_include/plugins/plg_isbn_includeフォルダを
//            作成してその中に入れてください。
//        ４．新規インストールの場合は、SETUP_ISBN.php をP_BLOGのルート（search.phpのある階層）にコピーします。
//        ５．http://www.example.com/p_blog/SETUP_ISBN.php にアクセスし、Setup is complete. と表示されたら、
//            記録用テーブルの作成成功です。SETUP_ISBN.php を削除してください。
//        ６．次に以下のファイルをHackします。
//            ★まずinclude/fnc_logs.inc.phpの189行目あたりfunction display_article_box($row) 内の、
//                // Convert Text to XHTML
//            という記述の前に
//            // modify doublebass
//            if (file_exists($cd . '/include/user_include/plugins/plg_isbn.inc.php')) {
//                include_once $cd . '/include/user_include/plugins/plg_isbn.inc.php';
//                $FKMM_isbn = new FKMM_isbn();
//                $row['comment'] = $FKMM_isbn->convert_isbn($row['comment']);
//            }
//            // modify end
//            を追加します。
//        ５．以上で設定完了です。
//            
//    ■使い方
//        ログを書く際に<!-- ISBN=xxxxxxxxx -->と記述すると表示の際に商品の情報に変換します。
//    ■サポート
//        ご意見、ご要望等あれば、福耳 Cafe掲示板までお気軽にご連絡ください。
//
//---------------------------------------------------------------------------------------------------

if (!extension_loaded('soap')) {
    if (file_exists($cd . '/include/user_include/plugins/plg_isbn_include/nusoap.php')) {
        require_once($cd . '/include/user_include/plugins/plg_isbn_include/nusoap.php');
    }
}


//----------------------------------------------------------------------------
//ISBN変換クラス
class FKMM_isbn {
    //アソシエイトID
    var $associate_id;
    //Develop Token
    var $develop_token;
    //テーブル名
    var $p_log_isbn_table;
    //取得したデータの有効期限
    var $expire;
    //デバッグ用フラグ
    var $debug = false;
    
    /**
     * @brief    コンストラクタ
     * @param    なし
     * @retval    function 
     * @date    2005/03/17 doublebass
     * @note    
     */
    function FKMM_isbn()
    {
        // --------------------------------------------
        // NOTE
        // --------------------------------------------
        // 以下のアソシエイトIDとDevelop Tokenはサンプルです。
        // あなたの取得したアソシエイトIDとDevelop Tokenに差し替えるよう編集してください。
        //
        //アソシエイトID
        $this->associate_id = 'jamlog-22';
        //Develop Token
        $this->develop_token = '1NYBBYEGW6K057GBE002';
        
        //テーブル名
        $this->p_log_isbn_table = "p_log_isbn";
        //有効期限は１週間
        $this->expire = 3600 * 24 * 7;
    }
    
    /**
     * @brief    コメント内のISBNの記述を変換
     * @param    $comment    (i)
     * @retval    変換後のコメント
     * @date    2005/03/17 doublebass
     * @note    
     */
    function convert_isbn($comment)
    {
        $text = $comment;
        $retv = preg_match_all('/<!-- ?ISBN=.* ?-->/', $text, $matches);
        if($this->debug == true){
            $text .= "<!-- ISBN変換プラグイン デバッグ用出力 ここから\n";
            $text .= "検出したISBNの数=" . $retv . "\n";
        }
        for ($i=0; $i< count($matches[0]); $i++) {
            $isbn_code = substr($matches[0][$i], 10, strpos($matches[0][$i], ' -->')-10);
            $amazon_text = $this->_get_product($isbn_code);
            if($this->debug == true){
                $text .= "検出したISBN=" . $isbn_code . "\n";
                $text .= "ISBNの検索結果=\n" . $amazon_text;
                $text .= "\n";
            }
            $text = preg_replace('/' .$matches[0][$i].'/', $amazon_text, $text);
        }
        if($this->debug == true){
            $text .= "ISBN変換プラグイン デバッグ用出力 ここまで -->\n";
        }
        return $text;
    }
    
    /**
     * @brief    商品の詳細を取得
     * @param    $isbn_code    (i)
     * @retval    商品の表示情報
     * @date    2005/03/17 doublebass
     * @note    
     */
    function _get_product($isbn_code)
    {
        $text = '';
        $sql = "SELECT * FROM " . $this->p_log_isbn_table . " WHERE asin='".$isbn_code."' LIMIT 1";
        if (!$res = mysql_query($sql)) {
            $text .= mysql_error();
            return $text;
        }
        $rows = mysql_num_rows($res);
        //データベースに登録されてないか、期限切れなら検索
        if ($rows != 0){
            $row = mysql_fetch_array($res);
            $expire = $this->expire;
            if ($row['date'] + $expire > time()){
                $not_found = false;
            }else{
                $not_found = true;
                //期限切れなら登録を削除
                $sql = 'DELETE FROM ' . $this->p_log_isbn_table . " WHERE asin='" . $isbn_code . "'";
                if (!mysql_query($sql)) {
                    $text .= mysql_error();
                    return $text;
                }

            }
        } else {
            $not_found = true;
        }
        

            $soap = new SoapClient("http://soap.amazon.co.jp/schemas3/AmazonWebServices.wsdl");
            $param = array(
                      'asin'     => $isbn_code,
                      'tag'      => $this->associate_id,
                      'type'     => 'lite',
                      'devtag'   => $this->develop_token,
                      'locale'   => "jp"
                      );
    
            // Request & Result
            $result = $soap->AsinSearchRequest($param);


//        $items = $result['Details'];
//        $items = $result->Details;

        //データベースに登録
        /*
        if ($not_found == true) {
            $data = serialize($items);
            $date = time();
            $sql  = 'INSERT INTO ' . $this->p_log_isbn_table . '(`asin`, `data`, `date`) '. 
                    "VALUES('{$isbn_code}', '{$data}', '{$date}'".")";
            if (!mysql_query($sql)) {
                $text .= mysql_error();
                return $text;
            }
        }
        */
        //詳細情報を取り出す
        foreach ($result->Details as $r) {
            $url = '';
            $title = '';
            $authors ='';
            $manufacturer = '';
//            $list_price = '';
            $our_price = '';
            $time = '';
            $availability = '';
            $image_url = '';//'http://images-jp.amazon.com/images/G/09/x-locale/detail/thumb-no-image.gif';
            if (isset($r->Url)) {
                $url = $r->Url;
            }
            if (isset($r->ProductName)) {
                $title = htmlspecialchars($r->ProductName);
            }
            if (isset($r->Authors)) {
                $authors = $r->Authors;
            }
            if(is_array($authors)){
                $authors = join(" , ", $authors);
            }
            if (isset($r->Manufacturer)) {
                $manufacturer = $r->Manufacturer;
            }
            if (isset($r->ListPrice)) {
                $list_price = $r->ListPrice;
            }
            if (isset($r->OurPrice)) {
                $our_price = $r->OurPrice;
            }
            if (isset($r->ReleaseDate)) {
                $time = $r->ReleaseDate;
            }
            if (isset($r->Availability)) {
                $availability = $r->Availability;
            }
            if (isset($r->ImageUrlMedium)) {
                $image_url = $r->ImageUrlMedium;
            }
            
            $text .= '<a href="' . $url . '">'
                    . '<img class="float-left" src="'
                    . $image_url
                    . '" title="'
                    . $title
                    . '" alt="'
                    . $title
                    . '" /></a>'
                    . $title . '<br />'
                    . '( '. $manufacturer .' )<br />'
                    ;
            if ($authors != "") {
                $text .= $authors . '<br />';
            } else {
                $text .= '';
            }
            $text .= $our_price . '<br class="float-clear" />';
        }
        return $text;
    }
}

// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])."/index.php");
}
?>