<?php
/**
 * Update Log Infomation in MySQL
 *
 * $Id: admin/updated.php, 2005/11/13 17:44:33 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/trackback/include/fnc_trackback.inc.php';
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
            $name     = insert_safe($_POST['name']);
            // $href     = $_POST['href'];
            // if posted category value were ended with ",(comma)", remove it.
            $category = preg_replace('/,+$/', '', insert_safe($_POST['category']));
            $comment  = insert_tag_safe($_POST['comment']);
                
            if ($cfg['enable_unicode'] == 'on') {
                mb_convert_variables($cfg['mysql_lang'], "auto", $name, $category, $comment);
            }

            // Update query
            $sql  = 'UPDATE ' . $log_table .
                    " SET `name` = '{$name}', `href` = '{$href}', `category` = '{$category}', `comment` = '{$comment}'";
            if (isset($_POST['no_update_mod'])) {
                $mod  = $_POST['mod'];
                $sql .= ", `mod` = '{$mod}'";
            } else {
                $cmod = gmdate('YmdHis', time() + ($cfg['tz'] * 3600));
                $sql .= ", `mod` = '{$cmod}'";
            }
            // Make private
            if (isset($_POST['private'])) {
                $sql .= ", `draft` = '1'";
            }
            
            $sql .= "WHERE `id` = '{$id}'";
            mysql_query($sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
            $new_id = $id; 
            $contents  = log_updated('index', 'check');
            $contents .= file_uploaded();
            $contents .= send_trackback();
            
            xhtml_output('');
            
        }
    } else{
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }

} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
