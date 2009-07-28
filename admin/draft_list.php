<?php
/**
 * Draft Log List
 *
 * $Id: admin/draft_list.php, 2004/10/04 22:29:28 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    
    $dsql = 'SELECT id, name, date FROM ' . $log_table . ' WHERE draft = 1 ORDER BY id DESC';
    $dres = mysql_query($dsql);

    $contents =<<<EOD
<h2>{$lang['draft_list']} : {$lang['log']}</h2>
<div class="section">
<table class="colored">
<tr>
<th class="colored">id</th>
<th class="colored">{$lang['title']}</th>
<th class="colored">{$lang['date']}</th>
<th class="colored">{$lang['edit']}</th>
</tr>
EOD;

    while ($row = mysql_fetch_array($dres)) {
        $row = convert_to_utf8($row);
        $contents .= "<tr>\n".
                     '<td class="colored">' . $row['id'] . "</td>\n".
                     '<td class="colored"><a href="../article.php?id='.$row['id'].'">' . $row['name'] . "</a></td>\n".
                     '<td class="colored">' . $row['date'] . "</td>\n".
                     '<td class="colored">'."\n".
                     '<form method="post" action="./draft_update.php">'."\n".
                     '<input type="hidden" name="mode" value="log" />'."\n".
                     '<input type="hidden" name="id" value="'.$row['id'].'" />'."\n".
                     '<input type="submit" value="'.$lang['edit'].'" />'."\n".
                     "</form>\n".
                     "</td>\n".
                     "</tr>\n";
    }
    $contents .= "</table>\n</div>\n";

    xhtml_output('');
    
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
