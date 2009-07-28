<?php
/**
 * Draft Log Preview
 *
 * $Id: admin/draft_peview.php, 2004/12/21 18:33:09 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';
require_once $cd . '/include/fnc_individual.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        // Next, pull out the data and display the preview.
        if ($cfg['trackback'] == 'on') {
            $selected  = ' selected="selected"';
            if (isset($_GET['tb_encode'])) {
                $tb_encode = $_GET['tb_encode'];
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
            } else {
                $opt1 = $selected;
                $opt2 = '';
                $opt3 = '';            
            }
            $checked  = ' checked="checked"';
            if (isset($_GET['up_ping'])) {
                switch ($_GET['up_ping']) {
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
            $tb_ping_uri = ', `ping_uri`';
        } else {
            $tb_ping_uri = '';
        }
        
        $sql = 'SELECT'.
               " `id`, `href`, `name`, `date`, DATE_FORMAT(`mod`,'%Y-%m-%d %T') as `mod`, `comment`, `category`" . $tb_ping_uri . ', `draft`' .
               " FROM `{$log_table}` WHERE `id` = {$id}";
        $res  = mysql_query($sql);
        $row  = mysql_fetch_array($res);
        $row = convert_to_utf8($row);
        
        if ($cfg['show_date_title'] == 'yes') {
            format_date($row_name = 'date');
            $title_date = $formatted_date;
            $date_section_begin  = '<div class="section">'."\n".
                                   '<h2 class="date-title">'.$title_date."</h2>\n";
            $date_section_end    = "\n</div><!-- End .section -->";
        } else {
            $date_section_begin = '';
            $date_section_end   = '';
        }
        
        $contents  = $date_section_begin;
        $contents .= display_article_box($row);
        
        // Reformat the modification time to "yyyymmddhms"
        $cmod  = preg_replace("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", "$1$2$3$4$5$6", $row['mod']);
        
        $contents .=<<<EOD

<form method="post" action="./draft_publish.php">
<p id="trackback-form">
<label for="send_ping_uri" accesskey="s">{$lang['tb_sendurl']}:</label><br />
<input type="text" id="send_ping_uri" name="send_ping_uri" size="40" accesskey="u" tabindex="5" value="{$row['ping_uri']}" class="bordered" />
<select tabindex="1" name="encode">
<option value="UTF-8"{$opt1}>UTF-8</option>
<option value="EUC-JP"{$opt2}>EUC-JP</option>
<option value="SJIS"{$opt3}>Shift_JIS</option>
</select>
</p>
<p>
{$lang['send_update_ping']} : 
<input tabindex="1" accesskey="n" type="radio" name="send_update_ping" value="no"{$up_ping_opt1} />No
<input tabindex="1" accesskey="y" type="radio" name="send_update_ping" value="yes"{$up_ping_opt2} />Yes
</p>
<div class="submit-button">
<input type="hidden" name="draft" value="0" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="mod" value="{$cmod}" />
<input tabindex="1" accesskey="p" type="submit" value="{$lang['publish']}" />
</div>
</form>
EOD;
        $contents .= file_uploaded();
        $contents .= $date_section_end;
        
        xhtml_output('');
        
    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
