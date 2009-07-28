<?php
/**
 * Insert New Log or Upload New Binary File into MySQL
 *
 * $Id: admin/draft_insert.php, 2005/11/13 17:43:09 Exp $
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
    if (isset($_POST['name'], $_POST['category'], $_POST['comment'], $_POST['href'])) {
        // Get the parameters posted from "add.php"
        if ($_POST['name'] == "") {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'.$lang['no_title']."</h3>\n";
        } elseif ($_POST['category'] == "") {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'.$lang['no_category']."</h3>\n";
        } elseif ($_POST['comment'] == "") {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'.$lang['no_comment']."</h3>\n";
        } else{ // if user & password auth success
            // if URI field is empty, set "http://". 
            if ($_POST['href'] == '') {
                $href = 'http://';
            } else {
                $href = insert_safe($_POST['href']);
            }
            // if date time is set, insert it. if not, set current timestamp (UTC + Offset).
            if ((isset($_POST['date'])) && 
                (isset($_POST['custom_date']) == 'yes') &&
                (preg_match("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", $_POST['date']))) {
                $date  = insert_safe($_POST['date']);
                $fdate = $date;
                $cmod  = preg_replace("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", "$1$2$3$4$5$6", $date);
            } else {
                $fdate = gmdate('Y-m-d H:i:s', time() + ($cfg['tz'] * 3600));
                $cmod  = gmdate('YmdHis',      time() + ($cfg['tz'] * 3600));
            }
            $name     = insert_safe($_POST['name']);
            // if posted category value were ended with ",(comma)", remove it.
            $category = preg_replace('/,+$/', '', insert_safe($_POST['category']));
            $comment  = insert_tag_safe($_POST['comment']);

            if ($cfg['enable_unicode'] == 'on') {
                $name     = mb_convert_encoding($name, $cfg['mysql_lang'], 'auto');
                $category = mb_convert_encoding($category, $cfg['mysql_lang'], 'auto');
                $comment  = mb_convert_encoding($comment, $cfg['mysql_lang'], 'auto');
            }
            
            // First, upload the attachment files
            file_upload();
            
            // Save Trackback Ping URI
            if ($cfg['trackback'] == 'on') {
                if (!empty($_POST['send_ping_uri'])) {
                    $tb_table       = ', `ping_uri`';
                    $senduri        = insert_safe($_POST['send_ping_uri']);
                    $tb_table_value = ", '" . $senduri . "'";
                    $tb_encode      = '&encode=' . insert_safe($_POST['encode']);
                } else {
                    $tb_table       = '';
                    $tb_table_value = '';
                    $tb_encode      = '';
                }
                if (!empty($_POST['send_update_ping'])) {
                    switch($_POST['send_update_ping']) {
                        case 'yes':
                            $up_ping = '&up_ping=yes';
                            break;
                        default :
                            $up_ping = '&up_ping=no';
                            break;
                    }
                } else {
                    $up_ping = '';
                }
            } else {
                $tb_table       = '';
                $tb_table_value = '';
                $tb_encode      = '';
                $up_ping        = '';
            }
            
            // Submit queries
            //
            // Next, insert the data
            $sql  = 'INSERT INTO ' . $log_table . '(`name`, `href`, `category`, `comment`, `date`, `mod`, `draft`' . $tb_table .') '. 
                    "VALUES('{$name}', '{$href}', '{$category}', '{$comment}', '{$fdate}', '{$cmod}', '1'". $tb_table_value .")";
            $res = mysql_query($sql) or die("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
            $id  = mysql_insert_id();
            if ($res) {
                header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir . '/draft_preview.php?id='.urlencode($id).$tb_encode.$up_ping);
                exit;
            }
        }

        xhtml_output('');
        
    } else{
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
