<?php
/**
 * Functions used in advanced file search only
 *
 * $Id: include/bin_search_plus.inc.php, 2005/02/09 13:30:15 Exp $
 */

/**
 * List up archives - 1
 */
function ls_archive1($sql, $d, $ti_num)
{
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
function ls_archive2($sql, $d, $ti_num) 
{
    global $lang;
    $res = mysql_query($sql);
    $row = mysql_fetch_row($res);
    $archives = '<input type="text" value="'.$row[0].'" name="'.$d.'" tabindex="'.$ti_num.'" size="10" maxlength="10" />';
    return $archives;
}


/**
 * List up by category
 */
function categories_name_array_plus()
{
    global $cfg, $info_table, $row;
    $sql = "SELECT `bin_category` FROM `{$info_table}` WHERE `draft` = '0'";
    $res = mysql_query($sql);
    $rowArray = array();
    while ($row = mysql_fetch_array($res)) {
        $row = convert_to_utf8($row);
        $token = strtok($row['bin_category'], ",");
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
 * Category Chooser for Binary File
 */
function select_categories_plus()
{
    global $cfg, $lang;
    $cat_list = '';
    foreach (categories_name_array_plus() as $str=>$num) {
        $cat_list .= '<input type="checkbox" name="cat[]" value="' .rawurlencode($str). '" />'.
                     htmlspecialchars($str). ' (' .$num. ")<br />\n";
    }
    return $cat_list;
}


/**
 * Main Interface of Advanced Binary File Search
 */
function display_search_plus() 
{
    global $cfg, $lang, $info_table;
    
    // Get archive list
    $sql = "SELECT DATE_FORMAT(`bindate`,'%Y-%m') as `bindate` FROM `{$info_table}` GROUP BY `bindate` ORDER BY `bindate`";
    $archives = ls_archive1($sql, $d = 'd', $ti_num = '3');
    
    
    // Get data for "archive1 between archive2"
    $sql1 = "SELECT `bindate` FROM `{$info_table}` GROUP BY `bindate` ORDER BY `bindate`";
    $archive1 = ls_archive2($sql = $sql1, $d = 'd1', $ti_num = '4');
    $sql2 = "SELECT `bindate` FROM `{$info_table}` GROUP BY `bindate` ORDER BY `bindate` DESC";
    $archive2 = ls_archive2($sql = $sql2, $d = 'd2', $ti_num = '5');
    
    // Categorie list
    $cat_list = select_categories_plus($info_table);
        
    $search_plus =<<<EOD
<form method="get" action="./search_plus.php">
<table class="colored" summary="Advanced Search">

<!--//====================// HEADER OF THE TABLE //=======================//-->
<thead>
<tr>
<th abbr="Advanced Search" colspan="2"><em>{$lang['file']}{$lang['advanced_search']}</em></th>
</tr>
</thead>

<tbody>

<!--//====================// 2nd ROW OF THE TABLE //=======================//-->
<tr>
<th abbr="Search Fields">{$lang['search_field']}</th>
<td>
<select name="f">
<option value="1" selected="selected">{$lang['title']} &amp; {$lang['comment']}</option>
<option value="2">{$lang['file_name']}</option>
<option value="3">{$lang['file_type']}</option>
<option value="4">{$lang['comment']}</option>
</select>
</td>
</tr>

<!--//====================// 3rd ROW OF THE TABLE //=======================//-->
<tr>
<th abbr="Keywords">{$lang['keyword']}</th>
<td>
<input onfocus="if (value == '{$lang['keyword']}') { value = ''; }" onblur="if (value == '') { value = '{$lang['keyword']}'; }" type="text" name="k" value="{$lang['keyword']}" tabindex="1" accesskey="k" size="32" />
<select name="ao">
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
<th abbr="Date">{$lang['date']}</th>
<td>
<input type="radio" name="ds" value="0" tabindex="1" accesskey="a" checked="checked" />{$lang['all_data']}<br />
<input type="radio" name="ds" value="1" tabindex="1" accesskey="m" />{$lang['by_month']}：
{$archives}<br />
<input type="radio" name="ds" value="2" tabindex="1" accesskey="b" />{$lang['between']}：
{$archive1}{$lang['and']}
{$archive2}
</td>
</tr>

<!--//====================// 5th ROW OF THE TABLE //=======================//-->
<tr>
<th abbr="Category">{$lang['category']}</th>
<td>
{$cat_list}
</td>
</tr>

</tbody>

<!--//====================// FOOTER OF THE TABLE //=======================//-->
<tfoot>
<tr>
<td colspan="2">
<input type="hidden" name="p" value="0" />
<input type="hidden" name="pn" value="1" />
<input type="submit" tabindex="2" accesskey="s" value="{$lang['go']}" />
</td>
</tr>
</tfoot>

</table>

</form>
EOD;
    return $search_plus;
}


//Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")){
	die("Hello, World! This is an include file.");
}
?>