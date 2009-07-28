<?php
/**
 * Main functions of COMMENT / FORUM
 *
 * $Id: fnc_forum.inc.php, 2005/01/21 13:12:15 Exp $
 */

// put topic_max value into $cfg['pagemax'] variable
$cfg['pagemax'] = $cfg['topic_max'];


/**
 * Article Box
 */
function display_forum_log_box($row) 
{
    global $cfg, $keys, $lang, $log_table, $comment_class, $forum_table,
           $cd, $row, $tid, $session_status, $rrow,
           $case, $i, $keys, $request_uri, $p, $pn;

    $row['title']      = sanitize($row['title']);
    $row['comment']    = sanitize($row['comment']);
    $row['user_name']  = sanitize($row['user_name']);
    $row['user_uri']   = sanitize($row['user_uri']);
    $row['date']       = sanitize($row['date']);
    $row['parent_key'] = sanitize(intval($row['parent_key']));
    
    hit_key_highlight();
    
    // Check the ID of the latest post
    $check_latest_sql = "SELECT `id` FROM `{$forum_table}` WHERE `tid` = '{$row['tid']}' AND `parent_key` = '0' AND `trash` = '0' ORDER BY `mod` DESC LIMIT 1";
    $check_latest_res = mysql_query($check_latest_sql);
    $check_latest_row = mysql_fetch_array($check_latest_res);
    if ($check_latest_row[0] == $row['id']) {
        $check_latest = ' id="latest"';
    } else {
        $check_latest = '';
    }
    
    if ($row['parent_key'] == 1) {
        if (isset($row['refer_id']) && ($row['refer_id'] != 0)) {
            $rsql = "SELECT `name` FROM `{$log_table}` WHERE `id` = '{$row['refer_id']}'";
            $rres  = mysql_query($rsql);
            $rrow  = mysql_fetch_array($rres);
            $rrow = convert_to_utf8($rrow);
            $anchor = '<h2>'.
                      '&#187; '.
                      '<a href="../article.php?id=' . $row['refer_id'] . '"> ' . $rrow['name'] . " </a></h2>\n";
            $top_title = '<div class="comments">'."\n".'<h3>' . $row['title'] . "</h3>\n";
        } else {
            $anchor = '';
            $top_title = '<div class="comments"'.$check_latest.'>'."\n".
                         '<h2 id="topic-title">' . $row['title'] . "</h2>\n"; // Parent post title
        }
        $comment = $anchor . $top_title;
    } else {
        $comment = '<div class="comments"'.$check_latest.'>'."\n".
                   '<h3>' . $row['title'] . "</h3>\n";
    }
    
    // in case ">" is posted...convert ">" to "&gt;" 
    //$row['comment'] = htmlspecialchars($row['comment']);
    
    // auto line-breaks
    $row['comment'] = nl2p($row['comment']);
     
    // generate URI
    if (isset($row['user_uri']) && preg_match('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)/', $row['user_uri'])) {
        $author = '<a href="'.$row['user_uri'].'">' . $row['user_name'] . '</a>'; 
    } else {
        $author = $row['user_name'];
    }
                    
    $comment .= '<p class="author">' .
                'From : ' . $author . ' @ ' . $row['date'] . ' ' .
                '<span class="edit"><a href="./modify.php?tid=' . $row['tid'] . '&amp;id='.$row['id'].'">'.$lang['edit']."</a></span>\n".
                '<span class="quote"><a href="./topic.php?tid='.$row['tid'] .'&amp;p='.$p.'&amp;pn='.$pn.'&amp;pm='.$cfg['pagemax'].'&amp;qid='.$row['id'].'#addform">'.$lang['quote']."</a></span></p>\n";
    $class_order = array_keys($comment_class);
    $color_class = $class_order[$row['color']];
    
    $comment .= '<div class="' . $color_class . '">'."\n" . $row['comment'] . "</div>\n";
    
    if (preg_match('/forum\/search.php/', $request_uri)) {
        $comment .= '<div class="a-footer"><a href="./topic.php?tid='.$row['tid'].'">'.$lang['topic'].'</a></div>';
    }
    
    // Display "Update" and "Delete" button while admin is logged-in.
    if ($session_status == 'on') {
        $comment .= '<form action="./admin/modify.php" method="post">'."\n".
                    '<div class="submit-button">'."\n".
                    '<input type="hidden" name="id" value="'.$row['id'].'" />'."\n".
                    '<input type="hidden" name="tid" value="'.$row['tid'].'" />'."\n".
                    '<input type="submit" value="'.$lang['mod'].'" />'."\n".
                    "</div>\n".
                    "</form>\n".
                    '<form method="post" action="./admin/delete.php">'."\n".
                    '<div class="submit-button">'."\n".
                    '<input type="hidden" name="id" value="'.$row['id'].'" />'."\n".
                    '<input type="hidden" name="tid" value="'.$row['tid'].'" />'."\n".
                    '<input type="submit" value="' . $lang['delete'] . '" />'."\n".
                    "</div>\n".
                    '</form>'."\n";
    } else {
        $comment .= '';
    }
    $comment .=  "</div>\n";
    $comment = smiley($comment);
    return $comment;
}




// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}
?>