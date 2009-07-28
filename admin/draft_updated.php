<?php
/**
 * Update Draft Log
 *
 * $Id: admin/draft_updated.php, 2005/11/13 17:43:31 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_POST['name'], $_POST['category'], $_POST['comment'], $_POST['href'])) {
        // Get the parameters posted from "update.php"
        if ($_POST['name'] == "") {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'.$lang['no_title']."</h3>\n";
        } elseif ($_POST['category'] == "") {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'.$lang['no_category']."</h3>\n";   
        } elseif ($_POST['comment'] == "") {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'.$lang['no_comment']."</h3>\n";
        } else {
            // if URI field is empty, set "http://". 
            if ($_POST['href'] == "") { $href = 'http://'; } else { $href = insert_safe($_POST['href']); }
            $id       = intval($_POST['id']);
                
            // User custom date & time
            if ((isset($_POST['date'])) &&
                (isset($_POST['custom_date']) == 'yes') &&
                (preg_match("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", $_POST['date']))) {
                $date     = insert_safe($_POST['date']);
                $cmod     = preg_replace("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", "$1$2$3$4$5$6", $date);
                    
                // Update old mod-time same as current datetime
                $new_date = "`date` = '" . $date . "'";
                $new_mod  = ", `mod` = '{$cmod}'";

            } else {
                $fdate    = gmdate('Y-m-d H:i:s', time() + ($cfg['tz'] * 3600));
                $cmod     = gmdate('YmdHis',      time() + ($cfg['tz'] * 3600));
                    
                // set current time (GMT + Offset) in SQL
                $new_date = "`date` = '{$fdate}'";
                // sync "date" and "mod"
                $new_mod  = ", `mod` = '{$cmod}'";
                    
            }
            $name     = insert_safe($_POST['name']);
            $href     = insert_safe($_POST['href']);
                
            // if posted category value were ended with ",(comma)", remove it.
            $category = preg_replace('/,+$/', '', insert_safe($_POST['category']));
            $comment  = insert_tag_safe($_POST['comment']);
                
            // Update Trackback Ping URI
            if ($_POST['send_ping_uri']) {
                $mod_ping_uri    = insert_safe($_POST['send_ping_uri']);
                $new_ping_uri = ", `ping_uri` = '{$mod_ping_uri}'";
            } else {
                $new_ping_uri = '';
            }
                
            if ($cfg['enable_unicode'] == 'on') {
                 mb_convert_variables($cfg['mysql_lang'], "auto", $name, $category, $comment);
            }

            // Submit query
            $sql  = 'UPDATE ' . $log_table .
                    " SET `name` = '{$name}', `href` = '{$href}', `category` = '{$category}', `comment` = '{$comment}', " . $new_date . $new_mod . $new_ping_uri .
                    " WHERE `id` = '{$id}'";
            mysql_query($sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
                
            if ($cfg['trackback'] == 'on') {
                if (isset($_POST['encode'])) {
                    $tb_encode = $_POST['encode'];
                    $selected  = ' selected="selected"';
                    switch($tb_encode) {
                        case 'EUC-JP':
                            $opt1 = '';
                            $opt2 = $selected;
                            $opt3 = '';
                            break;
                        case 'SJIS':
                            $opt1 = '';
                            $opt2 = '';
                            $opt3 = $selected;
                            break;
                        default :
                            $opt1 = $selected;
                            $opt2 = '';
                            $opt3 = '';
                            break;
                    }
                }
                $checked  = ' checked="checked"';
                if (isset($_POST['send_update_ping'])) {
                    switch ($_POST['send_update_ping']) {
                        case 'yes':
                            $up_ping_opt1 = '';
                            $up_ping_opt2 = $checked;
                            break;
                        default :
                            $up_ping_opt1 = $checked;
                            $up_ping_opt2 = '';
                            break;
                    }
                }
                $send_ping_uri = ', ping_uri';
            } else {
                $send_ping_uri = '';
            }
                
            // Next, pull out the data and display the preview.
            $sql = 'SELECT'.
                   " `id`, `href`, `name`, `date`, DATE_FORMAT(`mod`,'%Y-%m-%d %T') as `mod`, `comment`, `category`" . $send_ping_uri . ', `draft`' .
                   " FROM {$log_table} WHERE `id` = {$id}";
            $res = mysql_query($sql);
            $row = mysql_fetch_array($res);
            $row = convert_to_utf8($row);
            format_date($row_name = 'date');
            $title_date = $formatted_date;
            $contents  = '<div class="section">'."\n".
                         '<h2 class="date-title">'.$title_date."</h2>\n";
            $contents .= display_article_box($row);
                
            // Reformat the modification time to "yyyymmddhms"
            $cmod  = preg_replace("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", "$1$2$3$4$5$6", $row['mod']);
            $contents .=<<<EOD

<form method="post" action="./draft_publish.php">
<p id="trackback-form">
<label for="send_ping_uri" accesskey="s">{$lang['tb_sendurl']}:</label><br />
<input type="text" name="send_ping_uri" size="40" accesskey="u" tabindex="5" value="{$row['ping_uri']}" class="bordered" />
<select name="encode">
<option value="UTF-8"{$opt1}>UTF-8</option>
<option value="EUC-JP"{$opt2}>EUC-JP</option>
<option value="SJIS"{$opt3}>Shift_JIS</option>
</select>
</p>
<p>
{$lang['send_update_ping']} : 
<input type="radio" name="send_update_ping" value="no"{$up_ping_opt1} />No
<input type="radio" name="send_update_ping" value="yes"{$up_ping_opt2} />Yes
</p>
<div class="submit-button">
<input type="hidden" name="draft" value="0" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="mod" value="{$cmod}" />
<input type="submit" tabindex="1" accesskey="p" value="{$lang['publish']}" />
</div>
</form>
EOD;
            $contents .= file_uploaded();
            $contents .= "\n</div><!-- End .section -->\n";
        }

        xhtml_output('');

    } else{ // if user auth failed...
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
