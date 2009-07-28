<?php
/**
 * MySQL Database Status
 *
 * $Id: db_status.php, 2004/12/29 22:53:53 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

if ($session_status == 'on') {

    $contents =<<<EOD
<div class="section">
<h2>{$lang['system_admin']}</h2>
<ul class="flip-menu">
<li><a href="./admin_top.php">{$lang['sys_env']}</a></li>
<li><a href="./preferences.php">{$lang['preferences']}</a></li>
<li><a href="./edit_menu.php">{$lang['edit_custom_file']}</a></li>
<li><span class="cur-tab">{$lang['db_table_status']}</span></li>
</ul>
<form method="post" name="dbtables" action="./db_backup.php">
<p>
<input type="hidden" name="DBbackup" value="yes" />
</p>
<table rules="groups" frame="hsides" summary="DB status">
<thead>
<tr>
<th>Check</th>
<th>{$lang['table_name']}</th>
<th>{$lang['records']}</th>
<th>{$lang['size']}</th>
<th>{$lang['last_modified']}</th>
<th>{$lang['overhead']}</th>
<th>{$lang['optimize']}</th>
</tr>
</thead>
<tbody>
EOD;

        $sql = 'SHOW TABLE STATUS FROM `' . $dbname . '`';
        $res = mysql_query($sql);
        //-----------------------------
        // Status List (MySQL 4.0.x)
        //-----------------------------
        // [0]Name             
        // [1]Type             [2]Row_format   [3]Rows            [4]Avg_row_length   [5]Data_length
        // [6]Max_data_length  [7]Index_length [8]Data_free       [9]Auto_increment   [10]Create_time
        // [11]Update_time     [12]Check_time  [13]Create_options [14]Comment
        
        //-----------------------------
        // Status List (MySQL 4.1.x)
        //-----------------------------
        // [0]Name             [1]Engine
        // [2]Version          [3]Row_format   [4]Rows             [5]Avg_row_length  [6]Data_length
        // [7]Max_data_length  [8]Index_length [9]Data_free        [10]Auto_increment [11]Create_time
        // [12]Update_time     [13]Check_time  [14]Collation       [15]Checksum       [16]Create_options   [17]Comment

        while ($row = mysql_fetch_array($res)) {
        
            if (mysql_get_server_info() >= 4.1) {
                $data_length  = $row[6];
                $index_length = $row[8];
                $rows         = $row[4];
                $data_free    = $row[9];
                $update_time  = $row[12];
            } else {
                $data_length  = $row[5];
                $index_length = $row[7];
                $rows         = $row[3];
                $data_free    = $row[8];
                $update_time  = $row[11];
            }
            // total disk usage = data size + index size
            $size = $data_length + $index_length;
            if ($size < 1024000) {
                $size = $size / 1024;
                $size = round($size*10)/10;
                $unit = ' KB';
            } else {
                $size = $size / 1024000;
                $size = round($size*10)/10;
                $unit = ' MB';
            }
            // check overhead 
            if ($data_free > 0) {
                $overhead = '<span class="important">' . $data_free . ' byte</span>';
            } else {
                $overhead = $data_free . ' byte';
            }
            $contents .= "\n<tr>\n".
                         '<td>'."\n".
                         '<input type="checkbox" tabindex="4" id="tables" name="tables[]" value="'.$row[0].'" />'."\n".
                         "</td>\n".
                         '<td>' . $row[0]   . "</td>\n".
                         '<td>' . $rows     . "</td>\n".
                         '<td>' .
                         $size . $unit .
                         "</td>\n".
                         '<td>' . $update_time . "</td>\n".
                         '<td>' . $overhead . "</td>\n".
                         '<td>' .
                         '<a href="./db_optimize.php?table_name='.$row[0].'">'.
                         '<input type="button" value="'.$lang['optimize'].'" tabindex="4" accesskey="o" '.
                         'onClick="self.location.href=\'./db_optimize.php?table_name='.$row[0].'\'" />'.
                         '</a>'.
                         "</td>\n".
                         "</tr>";
        }
        $contents .=<<<EOD

</tbody>
</table>
<p class="selection-button">
<input type="button" tabindex="6" accesskey="s" onclick="selectTables(true);" value="{$lang['select_all']}" />
<input type="button" tabindex="7" accesskey="u" onclick="selectTables(false);" value="{$lang['select_off']}" />
</p>
<p class="submit-button">
<input type="submit" tabindex="8" accesskey="b" value="{$lang['backup']}" />
</p>
</form>
</div>
EOD;

     xhtml_output('');
     
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>