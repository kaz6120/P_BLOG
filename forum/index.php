<?php
/**
 * TOP OF THE FORUM
 * 
 * $Id: forum/index.php, 2005/01/31 01:32:14 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/search_plus.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once './include/fnc_search.inc.php';
require_once './include/fnc_forum.inc.php';

session_control();

against_xss();


//=====================================================
// PREPARE SEARCH QUERY
//=====================================================

if (!isset($_GET['k'], $_GET['p'], $_GET['d'], $_GET['c'], $_GET['pn'])) {
    $_GET['k']  = '';
    $_GET['p']  = '0';
    $_GET['d']  = '';
    $_GET['c']  = '0';
    $_GET['pn'] = '1';
}

if (isset($_GET['k'], $_GET['p'], $_GET['d'], $_GET['c'], $_GET['pn'])) {
    if ($cfg['enable_unicode'] == 'on') {
        $keyword = mb_convert_encoding(trim($_GET['k']), $cfg['mysql_lang'], "auto");
    } else {
        $keyword = trim($_GET['k']);
    }
    $page  = $_GET['p'];
    $date  = $_GET['d'];
    $case  = $_GET['c'];
    
    if (($page != '') & ($case != '')) {
        $sql = 'SELECT'.
               " `id`, `tid`, `parent_key`, `title`, `comment`, `user_name`, `user_pass`, `user_mail`, `date`,".
               " DATE_FORMAT(`mod`, '%Y/%m/%d %T') as `mod`".
               ' FROM `' .$forum_table. '`'.
               " WHERE `parent_key` = '1' AND `trash` = '0'";
        if ($date != "all") {
            $sql .= " AND (`date` LIKE '".$date."%')";
	    } else {
	        $sql .= '';
	    }
        $sql .= " ORDER BY `mod` DESC LIMIT {$page}, {$cfg['pagemax']} ";
    }
    //=====================================================
    // SUBMIT SEARCH QUERY
    //=====================================================
    $res = mysql_query($sql);
    if ($res) {
        $count_sql = "SELECT `id` FROM `{$forum_table}` WHERE `parent_key` = '1' AND `trash` = '0'";
        if ($date != "all") {
            $count_sql .= " AND (`date` LIKE '".$date."%')";
		}
        $hit_res = mysql_query($count_sql);
        $hit_row = mysql_num_rows($hit_res);
    
        // Show the hit data info.
        $rows = mysql_num_rows($res);
        if ($cfg['enable_unicode'] == 'on') {
            $keyword = mb_convert_encoding($keyword, "auto", $cfg['mysql_lang']);
        }

        //=================================================
        // SHOW THE RESULTS!
        //=================================================
        if ($hit_row) {
            // display the number of the thread
            $disp_page = $page + 1;
            $disp_rows = $page + $rows;

            if ($session_status == 'on') {
                $mod_del_button = '<th class="mod-del" summary="Delete">'.$lang['delete'].'</th>'."\n";
            } else {
                $mod_del_button = '';
            }
            
            // Create topic list
            $list = '';
            while ($row = mysql_fetch_array($res)) {
                
                // Check the number of replies
                $rep_sql = "SELECT COUNT(`id`) FROM `{$forum_table}` WHERE `tid` = '{$row['tid']}' AND `parent_key` = '0' AND `trash` = '0'";
                $rep_res = mysql_query($rep_sql);
                $rep_row = mysql_fetch_array($rep_res);                
                $row['title']     = htmlspecialchars($row['title']);
                $row['user_name'] = htmlspecialchars($row['user_name']);
                $row = convert_to_utf8($row);
                $row = smiley($row);
                
                // Status 
                if ($row['mod'] > (date('Y/m/d g:i:s', time() - 24*3600))) {
                    $status = ' class="status-on"';
                } else {
                    $status = '';
                }
                
                // Generate the link to the latest post in the last page.
                // p=  means the first post of each pages.
                // pn= means page number
                $pn = ceil(($rep_row[0] + 1) / $cfg['pagemax']);
                $p  = floor(($pn - 1) * $cfg['pagemax']);
                $query_to_thread = $row['tid'] .'&amp;p=0';
                $query_to_the_latest = '<a href="./topic.php?tid='.$row['tid'] .'&amp;p='.$p.'&amp;pn='.$pn.'&amp;pm='.$cfg['pagemax'].'#latest" class="latest-post" title="'.$lang['latest'].'">'.$row['mod'].'</a>';
                $list .= "<tr>\n".
                         '<td><a href="./topic.php?tid='. $query_to_thread .'"'.$status.'>' . $row['title'] . '</a></td>'."\n".
                         '<td>' . $rep_row[0] . "</td>\n".
                         '<td>' . $row['user_name'] . "</td>\n".
                         '<td class="last-modified">' . $query_to_the_latest . "</td>\n";
                if ($session_status == 'on') {
                    if ($cfg['xml_lang'] == 'ja') {
                        $confirm_delete = 'confirmDelete()';
                    } else {
                        $confirm_delete = 'confirmDelete_e()';
                    }
                    $list .= '<td class="colored">' . "\n".
                              '<form id="del-topic" method="post" action="./admin/delete.php" onsubmit="'.$confirm_delete.'">'."\n".
                              '<input type="hidden" name="tid" value="'.$row['tid'].'" />'."\n".
                              '<input type="submit" value="'.$lang['delete'].'" />'."\n".
                              "</form>\n".
                              "</td>\n";
                }
                $list .= "</tr>\n";
            }
            $list .= '';
            
            // Trash button
            if ($session_status == 'on') {
                $trash_sql = "SELECT COUNT(`id`) from `{$forum_table}` WHERE `trash` = '1'";
                $trash_res = mysql_query($trash_sql);
                $trash_row = mysql_fetch_array($trash_res);
                if ($trash_row[0] != 0) {
                    $trash_status = 'trash-full';
                    $trash_str    = '<a href="./admin/trash_list.php"><strong>Trash ';
                    $trash_num    = '(' . $trash_row[0] . ')</strong></a>';
                } else {
                    $trash_status = 'trash-empty';
                    $trash_str    = 'Trash';
                    $trash_num    = '';
                }
                $display_trash = '<p id="'.$trash_status.'">' . $trash_str . ' ' . $trash_num . '</p>';  

            } else {
                $display_trash = '';
            }
            
            // Load presentation template
            require_once './contents/index.tpl.php';
            
            $contents .= display_page_flip();
            
        } else {
            $disp_page = '';
            $disp_rows = '';
            $mod_del_button = '';
            $list = '';
            $display_trash = '';
            require_once './contents/index.tpl.php';
            $contents = $default;
        }
    } else {
        $contents = '<h2>'.$lang['no_tbl_msg'].'</h2>'.
                    '<h3>'.$lang['install_or_update_msg'].'</h3>'.
                    '<p class="ref"><a href="'.$cd.'/SETUP/index.php">'.$lang['install'].'</a></p>';
    }
} else {
    require_once './contents/index.tpl.php';
    $contents = $default;
}

xhtml_output('forum');

?>
