<?php
/**
 * Draft List
 *
 * $Id: admin/draft_list.php, 2004/10/04 22:35:30 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
     $dsql = 'SELECT id, bin_title, bindate FROM ' . $info_table . ' WHERE draft = 1 ORDER BY id DESC';
     $dres = mysql_query($dsql);
     $contents = '<h2>'.$lang['draft_list'].' : ' . $lang['file'] . "</h2>\n".
          '<table class="colored">'."\n".
          "<tr>\n".
          '<th class="colored">id</th>'."\n".
          '<th class="colored">'.$lang['title'].'</th>'."\n".
          '<th class="colored">'.$lang['date'].'</th>'."\n".
          '<th class="colored">'.$lang['edit'].'</th></tr>'."\n";
     while ($row = mysql_fetch_array($dres)) {
         $row = convert_to_utf8($row);
         $contents .= "<tr>\n".
                      '<td class="colored">' . $row['id'] . "</td>\n".
                      '<td class="colored"><a href="../files/article.php?id='.$row['id'].'">' . $row['bin_title'] . "</a></td>\n".
                      '<td class="colored">' . $row['bindate'] . "</td>\n".
                      '<td class="colored">'."\n".
                      '<form method="post" action="./bin_draft_update.php">'."\n".
                      '<input type="hidden" name="mode" value="bin" />'."\n".
                      '<input type="hidden" name="id" value="'.$row['id'].'" />'."\n".
                      '<input type="submit" value="'.$lang['edit'].'" />'."\n".
                      "</form>\n".
                      "</td>\n".
                      "</tr>\n";
     }
    $contents .= "</table>\n<br />\n";

    xhtml_output('');

} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
