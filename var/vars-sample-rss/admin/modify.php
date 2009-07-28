<?php
/**
 * RSS link - add
 *
 * $Id: rss/admin/add.php, 2005/01/22 23:29:32 Exp $
 */

$cd = '../../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    $sql = 'SELECT * FROM `p_rss_box`';
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows) {
        $form_table = '<table class="colored">'.
             '<tr><th>RSS</th><th>'.$lang['delete'].'</th></tr>';
        while ($row = mysql_fetch_array($res)) {
           $form_table .= "<tr>\n".
                 '<td>'.
                 '<form method="post" action="./modified.php">'.
                 'Name : <br />'.
                 '<input type="text" name="rss_name" value="' . $row['r_name'] . '" /><br />'.
                 'URI : <br />'.
                 '<input type="text" name="rss_uri" value="' . $row['r_uri'] . '" size="45" /><br />'.
                 'Category : <br />'.
                 '<input type="text" name="rss_category" value="' . $row['r_category'] . '" />'.
                 '<p>Add : ' . $row['r_date']. '<br />' .
                 'Mod : ' . $row['r_mod'] . '</p>'.
                 '<div class="submit-button">'.
                 '<input type="hidden" name="rss_id" value="' . $row['r_id'] . '" />'.
                 '<input type="submit" value="' .$lang['update'] . '"/>'.
                 '</div>'.
                 '</form>'.
                 '</td>' .
                 '<td>'.
                 '<form method="post" action="./delete.php">'.
                 '<input type="hidden" name="rss_id" value="' . $row['r_id'] . '" />'.
                 '<input type="submit" value="'.$lang['delete'].'" /></form>'.
                 '</td>'.
                 "</tr>\n";
        }
        
        $form_table .= "</table>\n<br />\n";
        $contents =<<<EOD
<div class="section">
<ul class="flip-menu">
<li><a href="./add.php">Feed RSS</a></li>
<li><span class="cur-tab">{$lang['mod_del']}</span><li>
</ul>
<p>{$lang['login_user']} : {$_SESSION['user_name']}</p>
{$form_table}
</div>
EOD;
    } else {
        $contents = <<<EOD
<div class="section">
<h2>No data found.</h2>
</div>
EOD;
    }
} else {
    $contents = bad_req_error();
}

xhtml_output('');

?>
