<?php
/**
 * Trackback
 *
 * 2004/05/09 first implementation. 
 * @author   : imksoo / Kajiya Takeshi
 * @modified : kaz
 *
 * $Id: fnc_trackback.inc.php, 2006-08-12 23:23:07 Exp $
 */

function display_trackback($row)
{
    global $trackback_table, $cfg, $lang, $id, $cd, $session_status, $trackbacks, $row, $article_addition, $email_link;
   
    $tb_query = 'SELECT * FROM `' . $trackback_table . "` WHERE `blog_id` = '" . $row['id'] . "' ORDER BY `id`";
    $tb_res = mysql_query($tb_query);
    if (!$tb_res) {
        return ' Trackback : Off';
        exit;
    }
    $tb_rows = mysql_num_rows($tb_res);

    if ($tb_rows == '0') {
        $tb_str = 'Trackback';
    } elseif ($tb_rows == '1') {
        $tb_str = $tb_rows . ' Trackback';
    } else {
        $tb_str = $tb_rows . ' Trackbacks';
    }
    
    // Article Addition
    if (empty($article_addition)) { $article_addition = ''; }
    
    // "View Trackback" popup title
    $view_tb_title = $lang['view_tb_title_1'] . htmlspecialchars(strip_tags($row['name'])) . $lang['view_tb_title_2'];
    
    if ($tb_rows != 0) { // When there are some trackbacks...
        $trackbacks = '';
        while ($tb_row = mysql_fetch_array($tb_res)) {
            $tb_row = convert_to_utf8($tb_row);
            $tb_id      = sanitize($tb_row['id']);
            $tb_title   = sanitize($tb_row['title']);
            $tb_excerpt = sanitize($tb_row['excerpt']);
            $tb_url     = sanitize($tb_row['url']);
            $tb_name    = sanitize($tb_row['name']);
            $tb_date    = sanitize($tb_row['date']);
            if ($session_status == 'on') { // When session status is on, display "Delete Ping" button.
                $delete_button = '<form action="'.$cd.'/trackback/admin/delete_ping.php" method="post">'."\n".
                                 '<div class="submit-button">'."\n".
                                 '<input type="hidden" name="id" value="' . $row['id'] . '" />'."\n".
                                 '<input type="hidden" name="ping_id" value="' . $tb_row['id'] . '" />'."\n".
                                 '<input tabindex="1" accesskey="d" type="submit" value="'.$lang['delete'].'" />'."\n".
                                 '</div>'."\n". 
                                 '</form>';
            } else {
                $delete_button = '';
            }
            $trackbacks .= '<h5 id="tb'.$tb_id.'"><a href="'.$tb_url.'">' . $tb_title . '</a></h5>'."\n" .
                           '<p>'.$tb_excerpt. "</p>\n".
                           '<p class="author">From : ' . $tb_name . ' @ ' . $tb_date . "</p>\n" . $delete_button ;
            $tb_status = ' class="status-on"';
        } 
    } else { // When No Trackbacks ....
        $trackbacks = '<p class="gray-out">No Trackbacks</p>';
        $tb_status  = '';
    }
    $tb_alert = (file_exists($cd . '/include/user_include/plugins/plg_trackback_spam_blocker.inc.php')) 
              ? '<br />（言及リンクのないトラックバックは無視されます）' 
              : '';
    $trackback_list =<<<EOD
<!-- Begin #trackback-list -->
<div id="trackback-list">
<h4 id="trackbacks">{$tb_str}</h4>
{$trackbacks}
<h5>Track from Your Website</h5>
<p class="trackback-uri">http://{$_SERVER['HTTP_HOST']}{$cfg['root_path']}trackback/tb.php?id={$row['id']}{$tb_alert}</p>
</div>
<!-- End #trackback-list -->

EOD;


    if (!empty($id)) { // When Permalink
        $trackback = $trackback_list;
    } else {
        $trackback = '<a href="./article.php?id='.$row['id'].'#trackbacks" title="'.$view_tb_title.'"'.$tb_status.'>' . $tb_str . '</a> ';
    }

    
    return $trackback;
}


function send_trackback()
{
    global $cd, $cfg, $lang, $id, $send_tb_result, $log_table;
    
    ////////////////// Sending Trackback Ping ////////////////////
    if ((!empty($_POST['send_ping_uri'])) && 
        (!empty($_POST['encode'])) &&
        ($_POST['send_ping_uri'] != 'http://')) {
        $ping_uri = $_POST['send_ping_uri'];
        $encode   = $_POST['encode'];
        
        $query  = 'SELECT `name`, `comment` FROM `' . $log_table . "` WHERE `id` = '" . $id . "'";
        $tb_res = mysql_query($query);
        $tb_row = mysql_fetch_array($tb_res);
        
        switch($encode) {
            case 'EUC-JP':
                $tb_row['name']    = mb_convert_encoding($tb_row['name'],    'EUC-JP', $cfg['mysql_lang']);
                $tb_row['comment'] = mb_convert_encoding($tb_row['comment'], 'EUC-JP', $cfg['mysql_lang']);
                break;
            case 'SJIS':
                $tb_row['name']    = mb_convert_encoding($tb_row['name'],    'SJIS',   $cfg['mysql_lang']);
                $tb_row['comment'] = mb_convert_encoding($tb_row['comment'], 'SJIS',   $cfg['mysql_lang']);
                break;
            default :
                if ($cfg['mysql_lang'] == 'UTF-8') {
                    break;
                } else {
                    $tb_row['name']    = mb_convert_encoding($tb_row['name'],    'UTF-8',  $cfg['mysql_lang']);
                    $tb_row['comment'] = mb_convert_encoding($tb_row['comment'], 'UTF-8',  $cfg['mysql_lang']);
                }
                break;     
        }
        
        $article_url     = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'article.php?id=' . $id;
        $article_title   = $tb_row['name'];
        
        // trim the posted strings

        // Convert Text to XHTML
        if (file_exists($cd . '/include/user_include/plugins/plg_markdown.inc.php')) {
            include_once $cd . '/include/user_include/plugins/plg_markdown.inc.php';
            $FKMM_markdown = new FKMM_markdown();
            $tb_row['comment'] = $FKMM_markdown->convert($tb_row['comment']);
        }
        $article_excerpt = mb_substr(strip_tags($tb_row['comment']), 0, 100, $encode) . '...';

        // send Ping to the target URI
        $target_uri = parse_url($ping_uri);
                
        if (!isset($target_uri['port'])) {
            $target_uri['port'] = 80;
        }

        if (isset($target_uri['query'])) {
            $target_uri['query'] = '?' . $target_uri['query'];
        } else { 
            $target_uri['query'] = '';
        }

        if (isset($target_uri['user'], $target_uri['pass'])) {
            $auth = 'Authorization: Basic ' . base64_encode($target_uri['user'] . ':' . $target_uri['pass']) . "\r\n";
        } else {
            $auth = '';
        }
        
        $para['url']       = $article_url;
        $para['title']     = $article_title;
        $para['excerpt']   = $article_excerpt;
        $para['blog_name'] = $cfg['blog_title'];
        while (list($key, $val) = each($para)) {
            $paras[] = $key . '=' . urlencode($val);
        }
        $data = join("&", $paras);
        
        // prepare the post value
        $post  = 'POST ' . $target_uri['path'] . $target_uri['query']. " HTTP/1.1\r\n".
                 'Host: ' . $target_uri['host'] . "\r\n".
                 'User-Agent: P_BLOG' . "\r\n" .
                 $auth .
                 'Content-Type: application/x-www-form-urlencoded' . "\r\n".
                 'Content-Length: ' . strlen($data) . "\r\n\r\n".
                 $data .
                 "\r\n";
   
        $fs = fsockopen($target_uri['host'], $target_uri['port']);
        if (!$fs) {
            return "Socket error!";
            $status = "<tr>\n".
                      '<td class="trackback-to">' . $ping_uri . "</td>\n".
                      '<td class="trackback-status">Error</td>'.
                      "\n</tr>\n";
        } else {
            fputs($fs, $post);       // send data...
            $res = fread($fs, 1024); // ...and get response
            
            // Read XML responses to check error
            if (preg_match('/<error>1<\/error>/', $res)) {
                $msg = '<span class="red">' . $lang['tb_ping_error'] . '</span>';
            } elseif (preg_match('/<error>0<\/error>/', $res)) {
                $msg = $lang['tb_ping_ok'];
            } else {
                $msg = $lang['tb_ping_no_res'];
            }
            
            // if sending Ping is success...
            $status = "<tr>\n".
                      '<td>' . $ping_uri . "</td>\n".
                      '<td>' . $msg . '</td>'.
                      "\n</tr>\n";
        }
    } else {
        $status = "<tr>\n".
                  '<td>Trackback Ping : '.$lang['none'].'</td>'."\n".
                  '<td>-</td>'.
                  "\n</tr>\n";
    }
    
    ////////////////// Sending Weblog Update Ping ////////////////////
    if (!empty($_POST['send_update_ping']) && ($_POST['send_update_ping'] == 'yes')) {
        $status2 = '';
        $ping_server_list = explode(",\r\n", stripslashes(trim($cfg['ping_server_list'])));
        foreach($ping_server_list as $ping_target) {
            $target_uri = parse_url($ping_target);
            $fp = fsockopen($target_uri['host'], 80, $errno, $errstr, 30);
            if (!$fp) {
                return 'Socket error!';
            } else {
                // prepare XML-RPC request
                $req_xml =   '<?xml version="1.0" encoding="UTF-8"?>'.
                             '<methodCall>'.
                             '<methodName>weblogUpdates.ping</methodName>'.
                             '<params>'.
                             '<param>'.
                             '<value>'.htmlspecialchars($cfg['blog_title']).'</value>'.
                             '</param>'.
                             '<param>'.
                             '<value>' . 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php</value>' .
                             '</param>'.
                             '</params>'.
                             '</methodCall>';
                // prepare the post value
                $post_ping = 'POST ' . $ping_target . " HTTP/1.1\r\n".
                             'Host: ' . $_SERVER['HTTP_HOST'] . "\r\n".
                             'User-Agent: P_BLOG XML-RPC' . "\r\n" .
                             'Content-Type: text/xml' . "\r\n".
                             'Content-Length: ' . strlen($req_xml) . "\r\n\r\n".
                             $req_xml.
                             "\r\n";     
                fputs($fp, $post_ping);       // send data...
                $ping_res = fread($fp, 4096); // ...and get response
            
                // Read XML responses to check error
                if (preg_match('/<boolean>1<\/boolean>/', $ping_res)) {
                    $ping_msg = '<span class="red">' . $lang['tb_ping_error'] . '</span>';
                } elseif (preg_match('/<boolean>0<\/boolean>/', $ping_res)) {
                    if (preg_match('/Thanks for your ping/', $ping_res)) {
                        $ping_msg = 'Thanks for your ping.';
                    } elseif (preg_match('/Thanks for the ping/', $ping_res)) {
                        $ping_msg = 'Thanks for the ping.';
                    } else {
                        $ping_msg = $lang['tb_ping_ok'];
                    }
                } else {
                    $ping_msg = '-';
                }
                // if receiving Ping response is success...
                $status2 .= "<tr>\n".
                            '<td>' . $ping_target . "</td>\n".
                            '<td>' . $ping_msg . '</td>'.
                            "\n</tr>\n";
            }
        }
        $status2 .= '';
    } else {
        $status2 = '';
    }
    // show results
    $send_tb_result =<<<EOD

<table summary="Trackbacks" class="colored">
<tr><th class="trackback-to">Sent Ping to</th><th class="trackbac-status">{$lang['tb_response']}</th></tr>
{$status}
{$status2}
</table>
<br />

EOD;
    return $send_tb_result;
}

?>