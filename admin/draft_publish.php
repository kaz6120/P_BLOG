<?php
/**
 * Update Log Infomation in MySQL
 *
 * $Id: admin/updated.php, 2004/12/16 06:37:24 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';
require_once $cd . '/trackback/include/fnc_trackback.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_POST['draft'], $_POST['id'], $_POST['mod']) &&
       (intval($_POST['draft']) == 0)) {
        $id  = $_POST['id'];
        
        /*
         (1)まずはログテーブルの一時保存のトラックバックURIの欄を空にし、公開。
         (2)最後にログデータを取り出す。
         (3)最後にトラックバックPingを送信し、一時保存しておいたPingURIを空にする。
         */
        
        $mod = $_POST['mod'];
        $sql = 'UPDATE ' . $log_table . " SET `draft` = '0', `mod` = '" . $mod . "' WHERE `id` = '" . $id . "'";
        $res = mysql_query($sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
        if ($res) {
            $contents = '<div class="section">'."\n".
                        '<h2 class="archive-title">'.$lang['published']."</h2>\n".
                        "</div>\n";
        }
        
        // Next, pull out the data and display the preview.
        $sql = 'SELECT'.
               " `id`, `href`, `name`, `date`, DATE_FORMAT(`mod`, '%Y-%m-%d %T') as `mod`, `comment`, `category`, `draft`".
               " FROM {$log_table} WHERE `id` = {$id}";
        $res  = mysql_query($sql);
        $row  = mysql_fetch_array($res);

        // Generate XHTML 
        $row = convert_to_utf8($row);
        $title_date = format_date($row_name = 'date');
        $contents .= '<div class="section">'."\n".
                     '<h2 class="date-title">'.$title_date."</h2>\n";
        $contents .= display_article_box($row);
        $contents .= file_uploaded();
        $contents .= send_trackback();
        $contents .= "</div><!-- End .section -->\n";

        xhtml_output('');

        
        /*
          一時保存したTrackback URIを空にしておく。
          理由は、公開後記事を修正する場合にTrackbackURI欄にこの既に送信したURIが再びセットされるため、
          同じ相手先にうっかり二重送信してしまう可能性があるため。
        */
        $sql = 'UPDATE ' . $log_table . " SET mod = '" . $mod . "', ping_uri = '' WHERE id = '" . $id . "'";
        $res = mysql_query($sql);
                

    } else{
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }

} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
