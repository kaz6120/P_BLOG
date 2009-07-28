<?php
/**
 * TRASH LIST OF THE FORUM
 * 
 * $Id: 2004/12/21 18:57:57 Exp $
 */

$cd = '../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/search_plus.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once '../include/fnc_search.inc.php';
require_once '../include/fnc_forum.inc.php';

session_control();

against_xss();


//=====================================================
// PREPARE SEARCH QUERY
//=====================================================

$contents =<<<EOD

<ul class="flip-menu">
<li><a href="../index.php">{$lang['topic_list']}</a></li>
<li><span class="cur-tab">Trash</span></li>
</ul>
EOD;

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
               " FROM `{$forum_table}`".
               " WHERE `trash` = '1'";

        if ($date != "all") {
            $sql .= " AND (`date` LIKE '".$date."%')";
	    }
        $sql .= " ORDER BY `mod` DESC LIMIT {$page}, {$cfg['pagemax']} ";
    }
    //=====================================================
    // SUBMIT SEARCH QUERY
    //=====================================================
    $res = mysql_query($sql);
    if ($res) {
        $count_sql = "SELECT `id` FROM `{$forum_table}` WHERE `trash` = '1'";
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

            // show tables!
            $contents .= '<p class="search-res">'.
                 '<span class="search-res">'.$hit_row.'</span>'.$lang['topics'].'&#160;'.
                 '<span class="search-res">'.$disp_page.' - '.$disp_rows.'</span> / '.
                 '<span class="search-res">'.$hit_row.'</span></p>'."\n".
                 // display thread list table
                 '<table id="forum-topic-table" summary="Topic List">'."\n".
                 "<tr>\n".
                 '<th abbr="Topic">'.$lang['topic'].'</th>'."\n".
                 '<th abbr="Author">'.$lang['posted_by'].'</th>'."\n".
                 '<th abbr="Modify">'.$lang['last_modified'].'</th>'."\n";
            if ($session_status == 'on') {
                $contents .= '<th class="mod-del" summary="Modify or Delte">'.$lang['mod_del'].'</th>'."\n";
            } else {
                $contents .= '';
            }
            $contents .= "</tr>\n";
            while ($row = mysql_fetch_array($res)) {
                
                $row = convert_to_utf8($row);
                
                $contents .= "<tr>\n".
                     '<td>' . $row['title'] . "</td>\n".
                     '<td>' . $row['user_name'] . "</td>\n".
                     '<td class="last-modified">' . $row['mod'] . "</td>\n";
                if ($session_status == 'on') {
                    $contents .= '<td class="colored">' . "\n".
                              '<form id="del-topic" method="post" action="./modify.php">'."\n".
                              '<input type="hidden" name="tid" value="'.$row['tid'].'" />'."\n".
                              '<input type="hidden" name="id" value="'.$row['id'].'" />'."\n".
                              '<input type="submit" value="'.$lang['mod'].'" />'."\n".
                              "</form>\n".
                              '<form id="del-topic" method="post" action="./delete.php">'."\n".
                              '<input type="hidden" name="from" value="trash" />'."\n".
                              '<input type="hidden" name="tid" value="'.$row['tid'].'" />'."\n".
                              '<input type="hidden" name="id" value="'.$row['id'].'" />'."\n".
                              '<input type="submit" value="'.$lang['delete'].'" />'."\n".
                              "</form>\n".
                              "</td>\n";
                }
                $contents .= "</tr>\n";
            }
            $contents .= "</table>\n<br />\n";
            
            $contents .= display_page_flip();
            
            if ($session_status == 'on') {
                $trash_sql = "SELECT COUNT(`id`) from `{$forum_table}` WHERE `trash` = '1'";
                $trash_res = mysql_query($trash_sql);
//                $trash_row = mysql_num_rows($trash_res);
                $trash_row = mysql_fetch_array($trash_res);
                if ($trash_row[0] != 0) {
                    $trash_status = 'trash-full';
                    $trash_str  = '<strong>Trash ';
                    $trash_num  = '(' . $trash_row[0] . ')</strong>';
                } else {
                    $trash_status = 'trash-empty';
                    $trash_str  = 'Trash';
                    $trash_num  = '';
                }
                $contents .= '<p id="'.$trash_status.'">' . $trash_str . ' ' . $trash_num . '</p>';

            }
        } else {
            $contents = '<h2>Trash is empty.</h2>'."\n";
        }
    } else {
        $contents = '<h2>'.$lang['no_tbl_msg'].'</h2>'.
                    '<h3>'.$lang['install_or_update_msg'].'</h3>'.
                    '<p>&gt;&gt;&#160;<a href="./SETUP/index.php">'.$lang['install'].'</a></p>';
    }
} else {
    $contents = '<h2>Trash is empty.</h2>'."\n";
}

xhtml_output('forum');

?>
