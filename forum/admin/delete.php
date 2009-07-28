<?php
/**
 * Delete Forum Logs
 *
 * $Id: forum/admin/delete.php, 2004/11/22 13:43:48 Exp $
 */

$cd = '../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once '../include/fnc_search.inc.php';
require_once '../include/fnc_forum.inc.php';

session_control();

if ($session_status == 'on') {
        if (isset($_POST['id'], $_POST['tid'])) {
            $id  = intval($_POST['id']);
            $tid = intval($_POST['tid']);
            $sql = "DELETE FROM `{$forum_table}` WHERE `id` = '" . $id . "'";
            $res = mysql_query($sql);
            if ($res) {
                if (isset($_POST['from']) && $_POST['from'] == 'trash') {
                    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/admin/trash_list.php');
                    exit;
                } else {
                    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/topic.php?tid=' . $tid);
                    exit;
                }
            }
        } elseif (isset($_POST['tid'])) {
            $tid = intval($_POST['tid']);
            
            // Delete completely
            $sql = "DELETE FROM `{$forum_table}` WHERE `tid` = '" . $tid . "'";

            $res = mysql_query($sql);
            if ($res) {
                header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/index.php');
                exit;
            }
        }    

} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/index.php');
    exit;
}
?>