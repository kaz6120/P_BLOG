<?php
/**
 * Functions used in advanced search only
 *
 * $Id: include/search_plus.inc.php, 2005/02/09 13:28:49 Exp $
 */


/**
 * List up archives - 1
 */
function ls_archive1($sql, $d, $ti_num) {
    global $lang;
    $res = mysql_query($sql);
    $archives = '<select name="'.$d.'" tabindex="'.$ti_num.'" title="'.$lang['show_archives'].'">'."\n";
    while ($date_row = mysql_fetch_row($res)) {
        $year_month = "".$date_row[0]."";
        $archives .= '<option>'.$year_month."</option>\n";
    }
    $archives .= "</select>\n";
    return $archives;
}


/**
 * List up archives - 2
 */
function ls_archive2($sql, $d, $ti_num) {
    global $lang;
    $res = mysql_query($sql);
    $row = mysql_fetch_row($res);
    $archive = '<input type="text" value="'.$row[0].'" name="'.$d.'" tabindex="'.$ti_num.'" accesskey="d" size="10" maxlength="10" />';
    return $archive;
}


/**
 * List up by category
 */
function categories_name_array_plus()
{ 
    global $cfg, $log_table, $row;
    $sql = "SELECT `category` FROM `{$log_table}` WHERE `draft` = '0'"; 
    $res = mysql_query($sql);
    $rowArray = array();
    while ($row = mysql_fetch_array($res)) {
        $row = convert_to_utf8($row);
        $token = strtok($row['category'], ",");
        while ($token) {
            array_push($rowArray, trim($token));
            $token = strtok(",");
        }
    }
    $rowArray = array_count_values($rowArray);
    ksort($rowArray, SORT_STRING);
    return $rowArray;
}


/**
 * Category Chooser
 */
function select_categories_plus()
{
    global $cfg, $lang;
    $cat_list = '';
    foreach (categories_name_array_plus() as $str => $num) {
        $cat_list .= '<input type="checkbox" name="cat[]" value="' .rawurlencode($str). '" tabindex="9" accesskey="c" />'.
                     htmlspecialchars($str). ' (' .$num. ")<br />\n";
    }
    return $cat_list;
}


/**
 * Main Interface of Advanced Search
 */
function display_search_plus() 
{
    global $cfg, $lang, $log_table;
    
    // Get archive list
    $sql = "SELECT DATE_FORMAT(`date`,'%Y-%m') as `date` FROM `" . $log_table . '` GROUP BY `date` ORDER BY `date` DESC';
    $archives = ls_archive1($sql, $d = 'd', $ti_num = '3');
    
    // Get data for "archive1 between archive2"
    $sql1 = 'SELECT `date` FROM `' . $log_table . '` GROUP BY `date` ORDER BY `date`';
    $archive1 = ls_archive2($sql = $sql1, $d = 'd1', $ti_num = '4');
    $sql2 = 'SELECT `date` FROM `' . $log_table . '` GROUP BY `date` ORDER BY `date` DESC';
    $archive2 = ls_archive2($sql = $sql2, $d = 'd2', $ti_num = '5');
    
    // Categorie list
    $cat_list = select_categories_plus();
    
    $search_plus =<<<EOD
<form method="get" action="./search_plus.php">
<table summary="Advanced Search">

<!--//====================// HEADER OF THE TABLE //=======================//-->
<thead>
<tr>
<th abbr="Advanced Search" colspan="2"><em>{$lang['advanced_search']}</em>
</th>
</tr>
</thead>

<!--//====================// FOOTER OF THE TABLE //=======================//-->
<tfoot>
<tr>
<td colspan="2">
<input type="hidden" name="p" value="0" />
<input type="hidden" name="pn" value="1" />
<input type="submit" tabindex="9" accesskey="s" value="{$lang['search']}" />
</td>
</tr>
</tfoot>

<tbody>

<!--//====================// 2nd ROW OF THE TABLE //=======================//-->
<tr>
<th abbr="Search Fields" class="colored-left">{$lang['search_field']}</th>
<td>
<select name="f" tabindex="1">
<option value="1" selected="selected">{$lang['title']} &amp; {$lang['comment']}</option>
<option value="2">{$lang['title']}</option>
<option value="3">URI</option>
<option value="4">{$lang['comment']}</option>
</select>
</td>
</tr>

<!--//====================// 3rd ROW OF THE TABLE //=======================//-->
<tr>
<th abbr="Keywords" class="colored-left">{$lang['keyword']}</th>
<td>
<input onfocus="if (value == '{$lang['keyword']}') { value = ''; }" onblur="if (value == '') { value = '{$lang['keyword']}'; }" type="text" name="k" tabindex="1" accesskey="k" value="{$lang['keyword']}" size="32" />
<select name="ao" tabindex="3">
<option value="AND">{$lang['all_words']}</option>
<option value="OR">{$lang['at_least_one']}</option>
</select>
<br />
{$lang['case']}
<input type="radio" name="c" value="0" tabindex="1" accesskey="i" checked="checked" />{$lang['insensitive']}
<input type="radio" name="c" value="1" tabindex="1" accesskey="v" />{$lang['sensitive']}
</td>
</tr>

<!--//====================// 4th ROW OF THE TABLE //=======================//-->
<tr>
<th abbr="Date" class="colored-left">{$lang['date']}</th>
<td>
<input type="radio" name="ds" value="0" tabindex="6" accesskey="a" checked="checked" />{$lang['all_data']}<br />
<input type="radio" name="ds" value="1" tabindex="7" accesskey="m" />{$lang['by_month']}：
{$archives}<br />
<input type="radio" name="ds" value="2" tabindex="8" accesskey="b" />{$lang['between']}：
{$archive1}{$lang['and']}
{$archive2}
</td>
</tr>

<!--//====================// 5th ROW OF THE TABLE //=======================//-->
<tr>
<th abbr="Category" class="colored-left">{$lang['category']}</th>
<td>
{$cat_list}
</td>
</tr>

</tbody>

</table>

</form>
EOD;
    return $search_plus;
}


// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}
?>