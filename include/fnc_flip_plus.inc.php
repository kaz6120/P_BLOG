<?php
/**
 * Flip page function for advanced search
 *
 * $Id: fnc_flip_plus.inc.php, 2005/12/17 21:12:17 Exp $
 */

function display_page_flip_plus() 
{
    global $cfg, $hit_row, $case, $field, $keyword, $ao, $date, $ds, $d1, $d2;
    
    $flip = '';
    if ($hit_row > $cfg['pagemax']) {
        if ($case == 0) {
            if ($cfg['page_flip_style'] == '1') {
                $all_flag = ($date == 'all') ? 'no' : 'yes';
                $flip = display_flip_link_plus($field, $keyword, $ao, $date, $ds, $d1, $d2, $case = '0', $hit_row, $all_flag);
            } else {
                $flip = display_flip_form_plus($field, $keyword, $ao, $date, $ds, $d1, $d2, $case = '0', $hit_row);
            }
        } else {
            if ($cfg['page_flip_style'] == '1') {
                $all_flag = ($date == 'all') ? 'no' : 'yes';
                $flip = display_flip_link_plus($field, $keyword, $ao, $date, $ds, $d1, $d2, $case = '1', $hit_row, $all_flag);
            } else {
                $flip = display_flip_form_plus($field, $keyword, $ao, $date, $ds, $d1, $d2, $case = '1', $hit_row);
            }
        }
    }

    return $flip;
}

function display_flip_link_plus($field, $keyword, $ao, $date, $ds, $d1, $d2, $case, $hit_row, $all_flag='yes') 
{
    global $cfg, $lang, $cd;

    $flip_link  = '<p class="flip-link">'."\n";
    $page_array = array();
    $array_key  = 0;
    $pagenumber = 0;
    $datalimit  = 0;
    $result     = 0;
    for ($datalimit; $datalimit < $hit_row; $datalimit += $cfg['pagemax']) {
        $pagenumber++;
        if (isset($_GET['pn'])) {
            if ($pagenumber == $_GET['pn']) {
                $tag_array["tag"] = '<strong>';
                $tag_array["anchor"] = $pagenumber . '</strong>';
                $page_array[] = $tag_array;
                $array_key = count($page_array) == 0 ? 0 : count($page_array) - 1;
            } else {
                $tag_array["tag"] = '<a href="' . $_SERVER['PHP_SELF'] . '?'.
                'k='       .$keyword.
                '&amp;ao=' .$ao. // plus
                '&amp;d='  .$date.
                '&amp;ds=' .$ds. // plus
                '&amp;d1=' .$d1. // plus
                '&amp;d2=' .$d2. // plus
                '&amp;p='  .$datalimit.
                '&amp;pm=' .$cfg['pagemax'].
                '&amp;pn=' .$pagenumber.
                '&amp;c='  .$case.
                '&amp;f='  .$field. // plus
                '">';
                $tag_array["anchor"] = $pagenumber. '</a>';
                $page_array[] = $tag_array;
            }
        }
    }
    if ($array_key > 0) {
        $flip_link .= '<span class="prev">'.$page_array[$array_key-1]["tag"].
                      $lang['prev'].
                      "</a></span>\n";
    }
    if ($all_flag == 'yes') {
        foreach($page_array as $value) {
            $flip_link .= $value["tag"].$value["anchor"]."\n";
        }
    }
    if (isset($_GET['pn']) && $_GET['pn'] != $pagenumber) {
        $flip_link .= '<span class="next">'.$page_array[$array_key+1]["tag"].
                      $lang['next'].
                      "</a></span>\n";
    }
    $flip_link .= "</p>\n";

    return $flip_link;
}


function display_flip_form_plus($field, $keyword, $ao, $date, $ds, $d1, $d2, $case, $hit_row) 
{
    global $cfg, $lang;
    $flip_form =<<<EOD

<form action="{$_SERVER['PHP_SELF']}" method="get">
<div class="flip-form">
<input type="hidden" name="f" value="{$field}" />
<input type="hidden" name="k" value="{$keyword}" />
<input type="hidden" name="ao" value="{$ao}" />
<input type="hidden" name="d" value="{$date}" />
<input type="hidden" name="ds" value="{$ds}" />
<input type="hidden" name="d1" value="{$d1}" />
<input type="hidden" name="d2" value="{$d2}" />
<input type="hidden" name="pm" value="{$cfg['pagemax']}" />
<input type="hidden" name="pn" value="" />
<input type="hidden" name="c" value="{$case}" />
<select class="resultchange" name="p" tabindex="1" onchange="this.form.submit()">
<option value="">{$lang['flip_pages']}</option>
EOD;
        //(1)separate the hit data
        //(2)->increase the option tags to the result
        $pagenumber = 0;
        $datalimit  = 0;
        $result     = 0;
        for ($datalimit; $datalimit < $hit_row; $datalimit += $cfg['pagemax']) {
            $pagenumber += 1;
            $result = $datalimit+1;
            $flip_form .= '<option value="'.$datalimit.'">'.$result.' - (  P '.$pagenumber." )</option>\n";
        }
    $flip_form .=<<<EOD
</select>
<noscript> 
<div class="noscript"><input type="submit" accesskey="f" tabindex="2" value="{$lang['flip']}" /></div>
</noscript>
</div>
</form>

EOD;
    return $flip_form;
}

/**
 * Deny direct access to this file
 */
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
	die("Hello, World! This is an include file.");
}
?>