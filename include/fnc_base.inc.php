<?php
/**
 * Base Functions (Common for ALL pages)
 *
 * @since 2005/11/13 18:05:02 updated: 2006-09-29 09:24:29
 */

include_once $cd . '/include/user_include/css_rss.inc.php';

//================================================================
// Load Modules
//================================================================
/*
 * Load Plug-in Modules
 */
function include_plugin($mode)
{
    global $cd, $cfg, $plugin;
    if ($handlerDir = @opendir($cd . '/include/user_include/plugins')) {
        while ($filename = readdir($handlerDir)) {
            if ($filename != '.' && $filename != '..' && preg_match('/^plg_.+\.inc\.php$/', $filename)) {
                include_once $cd . '/include/user_include/plugins/' . $filename;
            }
        }
    }
}

/*
 * Default XHTML
 */
function default_xhtml($contents)
{
        $cd = '.';
        $style_num = 'rich_green';
        $style = 'default';
        $page_title = 'Untitled';
        $cfg['blog_title'] = 'Untitled';
        $dtd =<<<EODTD
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
EODTD;
        $content_type =<<<EOCT
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-style-type" content="text/css" />
EOCT;
        $content_menu = 'Menu Undefined';
        $footer_content = 'Footer Undefined';
        include_once $cd . '/include/user_include/base_xhtml.inc.php';
}

//================================================================
// MySQL
//================================================================
/**
 * MySQL Connection
 */
function db_connect() 
{
    global $dbname, $host, $user, $password;
    $link = @mysql_connect($host, $user, $password);
    if ($link && mysql_select_db($dbname)) {
        return $link;
    } else {
        if (file_exists('./SETUP/')) {
            $update_dir = '<p class="ref">Go to <a href="./SETUP/">SETUP</a> directory.</p>';
        } else {
            $update_dir = '<p>No SETUP directory found.</p>';
        }        
        $message =<<<EOD
<div class="important">
<h2>Database Not Connected.</h2>
<h3 class="warning">Please setup the database.</h3>
{$update_dir}
</div>
EOD;
        die(default_xhtml($message));
        exit;
        return FALSE;
    }
}


/**
 * Unicode converting
 */
function convert_to_utf8($row) 
{
    global $cfg, $cd;
    if ($cfg['enable_unicode'] == 'on') {
        if (function_exists('mb_convert_encoding')) {
            if (is_array($row)) {
			      /*
			      $key = array_keys( $row );
			      $val = array_values( $row );
			      $n = count($key); 
			      for($i=0; $i<$n; $i++) {
			      */
			      while (list ($key, $val) = each ($row)) {
			          $row[$key] = mb_convert_encoding($val, 'UTF-8', $cfg['mysql_lang']);
    			  }
            }
        }
    }
    return $row;
}


function utf8_convert($str)
{
    global $cfg;    
    if ($cfg['enable_unicode'] == 'on') {
        $str = mb_convert_encoding($str, 'UTF-8', $cfg['mysql_lang']);
    }
    return $str;
}


function convert_from_utf8($str)
{
    global $cfg;
    if ($cfg['enable_unicode'] == 'on') {
        $str = mb_convert_encoding($str, $cfg['mysql_lang'], 'UTF-8');
    }
    return $str;
}


/*
 * Replacement of html_entity_decode(); for older than PHP4.3.0
 * @author: PHP.net <http://jp2.php.net/html_entity_decode>
 */
function unhtmlentities($string) 
{
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
}

//================================================================
// CONFIG
//================================================================

function init_config()
{
    global $config_table, $cd, $cfg;
    $cfg = array();
    $sql = 'SELECT * FROM `' . $config_table . '`';
    $res = mysql_query($sql);
    if ($res) {
        while ($row = mysql_fetch_assoc($res)) {
            $cfg[$row['config_key']] = $row['config_value'];
        }
    } else {

        if (file_exists('./SETUP/')) {
            $update_dir = '<p class="ref">Go to <a href="./SETUP/">SETUP</a> directory.</p>';
        } else {
            $update_dir = '<p>No SETUP directory found.</p>';
        }        
        $message =<<<EOD
<div class="important">
<h2>No Config Table Found.</h2>
<h3 class="warning">Please Install Config Table.</h3>
{$update_dir}
</div>
EOD;
        die(default_xhtml($message));
        exit;
    }
    // Multi-byte
    if (($cfg['enable_unicode'] == 'on') && 
       (function_exists('mb_convert_variables'))) {
        mb_convert_variables("UTF-8", $cfg['mysql_lang'], $cfg);
    }
    return $cfg;
}


//================================================================
// SESSION
//================================================================
function session_control()
{
    global $cfg, $cd, $session_status, $user_table, $admin, $admin_dir;

    if ($cfg['use_session_db'] == 'yes') {
        require_once $cd . '/' . $admin_dir . '/db_session.php';
    } else {
        session_name($cfg['p_blog_sess_name']);
        session_start();
    }
    if (isset($_SESSION['admin_login'], $_SESSION['user_name'], $_SESSION['user_pass'])) {
        $sql = "SELECT `user_id` FROM `{$user_table}`".
               " WHERE `user_pass` = '". $_SESSION['user_pass'] ."'".
               " AND `user_name` = '". $_SESSION['user_name'] ."' LIMIT 1";
        $res = mysql_query($sql);
        $row = mysql_num_rows($res);
        if ($row != 0) {
            $session_status = 'on';
            $admin = 'yes';
        } else {
            $session_status = 'off';
            $admin = '';
            $_SESSION['admin_login'] = 0;
            $_SESSION['user_name']   = 0;
            $_SESSION['user_pass']   = 0;
            session_unset();
            session_destroy();
               
        }
    } else {
        $session_status = 'off';
        $admin = '';  
        $_SESSION['admin_login'] = 0;
        $_SESSION['user_name']   = 0;
        $_SESSION['user_pass']   = 0;
        session_unset();
        session_destroy();
    }
}

//================================================================
// OUTPUT XHTML
//================================================================

function xhtml_output($mode) 
{
    global $cfg, $lang, $style_num, $style,
           $cd, $begin_time_str, $admin, $footer_content,
           $dtd, $page_title, $content_type, $alternate_link_rss,
           $subtitle, $contents_top, $contents,
           $admin_sess_menu, $content_menu, $search_form,
           $menu_middle, $archive_by_date, $category_menu,
           $file_type_menu, $recent_entries, $recent_comments,
           $recent_trackbacks, $css_switch, $rss_button, $rss2_button,
           $menu_bottom, $request_uri, $p_blog_path, $plugin;

    include_plugin($mode);

    xhtml_header();

    xhtml_menu($mode);
    
    // Contents Top
    if ($request_uri == 'http://'. $_SERVER['HTTP_HOST'] . $cfg['root_path']. 'index.php') {

        if (file_exists($cd . '/include/user_include/contents_top.inc.php')) {
            include_once $cd . '/include/user_include/contents_top.inc.php';
        } else {
            $contents_top = '';
        }
    } else {
        $contents_top = '';
    }
    
    // Choose Footer
    if ($cfg['footer_type'] == 1) {
        $footer_content = display_footer($cd);
    } elseif ($cfg['footer_type'] == 2) {
        $footer_content = display_valid_footer($style_num, $style, $cd);
    } elseif ($cfg['footer_type'] == 3) {
        $footer_content = display_w3c_valid_footer($style_num, $style, $cd);
    } else {
        $footer_content = display_user_footer($admin=FALSE);
    }
    
    // Load Template
    require_once $cd . '/include/user_include/base_xhtml.inc.php';
    /*
    if ($request_uri == $p_blog_path . 'var/vars-sample/index.php') {
        require_once $cd . '/include/user_include/vars_xhtml.inc.php';
    } else {
        require_once $cd . '/include/user_include/base_xhtml.inc.php';
    }
    */
}

//================================================================
// HEADER-BOX
//================================================================
/**
 * XHTML Header
 */
function xhtml_header() 
{
    global $http, $cfg, $lang, $cd, $style_num, $style, $row, $admin_dir,
           $request_uri, $vars_page_title, $more_vars_dir, $more_vars_title,
           $search_form, $table, $log_table, $info_table, $p_blog_path,
           $dtd, $content_type, $page_title, $alternate_link_rss, $subtitle;
    
    
    // Generating Page Titles and Show & Hide Search Form
    $separator   = ' : ';
    $p_blog_path = $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'];
    
    // Main Pages
    if ($request_uri == $p_blog_path . 'index.php') {
        $page_title  = $cfg['blog_title'];
        $search_form = search_form($plus = 'yes');
        $table       = $log_table;
    } elseif (preg_match('/files/', $request_uri)) {
        if (preg_match('/search_plus/', $request_uri)) {
            $page_title = $cfg['blog_title'] . $separator . $cfg['file_index_title'] . $separator . $lang['advanced_search'];
            $search_form = '';
        } elseif ((preg_match('/article.php/', $request_uri)) && (isset($row['bin_title']))) { // Permalink
            $page_title  = $cfg['blog_title'] . $separator . $cfg['file_index_title'] . $separator . $row['bin_title'];
            $search_form = search_form($plus = 'yes');
        } else {
            $page_title = $cfg['blog_title'] . $separator . $cfg['file_index_title'];
            $search_form = search_form($plus = 'yes');
        }
        $table       = $info_table;
        
    } elseif ($request_uri == $p_blog_path .  'var/index.php') {
        $page_title  = $cfg['blog_title'] . $separator . $vars_page_title;
        $search_form = '';
        $table       = '';
    } elseif ($request_uri == $p_blog_path . $more_vars_dir . '/index.php') {
        $page_title  = $cfg['blog_title'] . $separator . $more_vars_title;
        $search_form = '';
        $table       = '';
    } elseif (($request_uri == $p_blog_path . 'search.php') or ($request_uri == $p_blog_path . 'files/search.php')) {
        $page_title  = $cfg['blog_title'] . $separator . $lang['archives'];
        $search_form = search_form($plus = 'yes');
        $table       = '';
    } elseif (($request_uri == $p_blog_path . 'search_plus.php') or ($request_uri == $p_blog_path . 'files/search_plus.php')) {
        $page_title  = $cfg['blog_title'] . $separator . $lang['advanced_search'];
        $search_form = '';
        $table       = '';
    } elseif ($request_uri == $p_blog_path . 'category.php') {
        $page_title  = $cfg['blog_title'] . $separator . $lang['category'];
        $search_form = search_form($plus = 'yes');
        $table       = $log_table;
    } elseif ($request_uri == $p_blog_path . 'files/category.php') {
        $page_title  = $cfg['blog_title'] . $separator . $lang['category'];
        $search_form = search_form($plus = 'yes');
        $table       = $info_table;
    } elseif (preg_match('/forum/', $request_uri)) {
        if (preg_match('/admin/', $request_uri)) {
            $page_title = $cfg['blog_title'] . $separator . $lang['admin'] . $separator . $lang['forum'];
        } elseif (preg_match('/comment/', $request_uri)) {
            $page_title = $cfg['blog_title'] . $separator . $lang['comment'];
        } else {
            $page_title = $cfg['blog_title'] . $separator . $lang['forum'];
        }
        $search_form = search_form($plus = 'no');
        $table       = '';
    } elseif (preg_match('/trackback/', $request_uri)) {
        $page_title  = $cfg['blog_title'] . $separator . 'Trackback';
        $search_form = '';
        $table       = '';
    // Permalink Page
    } elseif ((preg_match('/article.php/', $request_uri)) && (isset($row['name']))) {
        $page_title  = $cfg['blog_title'] . $separator . $row['name'];
        $search_form = search_form($plus = 'yes');
        $table       = $log_table;
    // Admin pages
    } elseif (preg_match('/'.$admin_dir.'/', $request_uri)) {
        $page_title  = $cfg['blog_title'] . $separator . $lang['admin'];
        $search_form = '';
        $table       = '';
    } else {
        $page_title  = $cfg['blog_title'];
        $search_form = '';
        $table       = '';
    }
    // Additions
    if (isset($_GET['k']) && ($_GET['k'] != '')) {
        $k = htmlspecialchars($_GET['k']);
        $page_title .= $separator . $k;
    }
    if (isset($_GET['d']) && ($_GET['d'] != '')) {
        if (!isset($_GET['ds'])) {
            $d = htmlspecialchars($_GET['d']);
            $page_title .= $separator . $d;
        }
    }
    $p_blog_version = P_BLOG_VERSION;
    // Switch XML DTD : XHTML1.0 Strict or XHTML1.1
    if (($cfg['xml_version'] == '1.1') || ($cfg['xml_version'] == '1.1cn')) {
        $dtd =<<<EODTD
<?xml version="1.0" encoding="{$cfg['charset']}"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
                      "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$cfg['xml_lang']}">
EODTD;
        $content_type = '';
    } else {
        $dtd =<<<EODTD
<?xml version="1.0" encoding="{$cfg['charset']}"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$cfg['xml_lang']}" lang="{$cfg['xml_lang']}">
EODTD;
        $content_type =<<<EOCT
<meta http-equiv="content-type" content="text/html; charset={$cfg['charset']}" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-style-type" content="text/css" />

EOCT;
    }

    // Generate alternate link / RSS link
    if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
        $alternate1 = $p_blog_path . 'rss/1.0.php?id=' . $_GET['id'];
        $alternate2 = $p_blog_path . 'rss/2.0.php?id=' . $_GET['id'];
    } elseif (preg_match('/forum/', $request_uri)) {
        if (isset($_GET['tid'])) {
            $alternate1 = $p_blog_path . 'rss/1.0.php?tid=' . $_GET['tid'];
            $alternate2 = $p_blog_path . 'rss/2.0.php?tid=' . $_GET['tid'];
        } else {
            $alternate1 = $p_blog_path . 'rss/1.0.php?f_index';
            $alternate2 = $p_blog_path . 'rss/2.0.php?f_index';
        }
    } elseif ($request_uri == $p_blog_path . 'index.php') {
        $alternate1 = $p_blog_path . 'rss/recent.php';
        $alternate2 = $p_blog_path . 'rss/2.0.php';
    } else {
        $alternate1 = '';
        $alternate2 = '';
    }
    $rss_link_1 = ($alternate1 == '') ? '' : 
        '<link rel="alternate" type="application/rss+xml" title="RSS 1.0" href="'.$alternate1.'" />'."\n";
    $rss_link_2 = ($alternate2 == '') ? '' : 
        '<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="'.$alternate2.'" />'."\n";
    $alternate_link_rss = $rss_link_1 . $rss_link_2;
    // Generate subtitle
    $subtitle = ($cfg['sub_title'] == '') ? '' : '<span id="subtitle">' . htmlspecialchars($cfg['sub_title']) . '</span>'."\n";
    
}


/**
 * Log Search Forms
 */
function search_form($plus)
{
    global $cfg, $lang, $form_length, $request_uri;
    
    if ($plus == 'yes') {
        $search_plus =<<<EOD
<span id="search-plus">
<a href="./search_plus.php" title="{$lang['advanced_search']}">{$lang['advanced_search']}</a>
</span>
EOD;
    } else {
        $search_plus = '';
    }
    
    if (preg_match('/forum/', $request_uri)) {
        $search_target = $lang['forum'];
    } elseif (preg_match('/files/', $request_uri)) {
        $search_target = $lang['file'];
    } else {
        $search_target = '';
    }
    
    $search_form =<<<EOD
<div class="menu" id="search-menu">
<h2><label for="keyword">{$search_target}{$lang['search']}</label></h2>
<form id="search" action="./search.php" method="get">
<p>
<input onfocus="if (value == '{$lang['keyword']}') { value = ''; }" onblur="if (value == '') { value = '{$lang['keyword']}'; }" tabindex="1" accesskey="k" type="text" id="keyword" name="k" value="{$lang['keyword']}" title="{$lang['enter_keywords']}" />
<input type="submit" accesskey="g" tabindex="2" id="search-button" value="Go" />{$search_plus}
<input type="hidden" name="d" value="" />
<input type="hidden" name="p" value="0" />
<input type="hidden" name="c" value="0" />
<input type="hidden" name="pn" value="1" />
<input type="hidden" name="f" value="" />
</p>
</form>
</div>
EOD;
    return $search_form;
}


//================================================================
// CONTENT-BOX
//================================================================

/**
 * Article Date Format
 */
function format_date($row_name) 
{
    global $cfg, $row, $formatted_date;
    switch ($cfg['date_style']) {
        case '1': // 2005/01/01
            $row[$row_name] = date('Y/m/d g:i:s a', strtotime($row[$row_name]));
            $formatted_date = substr($row[$row_name], 0, 10);
            break;
        case '2': // Jan 01, 2005
            $row[$row_name] = date("M d, Y g:i:s a", strtotime($row[$row_name]));
            $formatted_date = substr($row[$row_name], 0, 12);
            break;
        default: // 2005-01-01
            $formatted_date = substr($row[$row_name], 0, 10);
            break;
    }
    return $formatted_date;
}

/**
 * Page flipper
 */
function display_page_flip() 
{
    global $cfg, $hit_row, $case, $field, $keyword, $date;
    $keyword = urlencode($keyword);
    if ($hit_row > $cfg['pagemax']) {
        if ($case == 0) {
            if ($cfg['page_flip_style'] == '1') {
                if ($date == 'all') {
                   $flip = display_flip_link($field = '', $keyword, $date, $case = '0', $hit_row,'no');
                } else {
                   $flip = display_flip_link($field = '', $keyword, $date, $case = '0', $hit_row,'yes');
                }
            } else {
                $flip = display_flip_form($field = '', $keyword, $date, $case = '0', $hit_row);
            }
        } else {
            if ($cfg['page_flip_style'] == '1') {
                $flip = display_flip_link($field = '', $keyword, $date, $case = '1', $hit_row);
            } else {
                $flip = display_flip_form($field = '', $keyword, $date, $case = '1', $hit_row);
            }
        }
    } else {
        $flip = '';
    }
    return $flip;
}


/**
 * Page Flip Style 1 : Link
 * @modified : nakamuxu
 */
function display_flip_link($field, $keyword, $date, $case, $hit_row, $all_flag='yes') 
{
    global $cfg, $lang, $cd, $tid, $request_uri, $p_blog_path;
    
    // Add "tid" parameter when in forum mode.
    if (preg_match('/forum/', $request_uri)) {
        $topic_id = '&amp;tid='. $tid;
        $cfg['pagemax'] = $cfg['topic_max']; 
    } else {
        $topic_id = '';
    }
    
    $flip_link  = '<p class="flip-link">'."\n";
    $page_array = array();
    $array_key  = 0;
    $pagenumber = 0;
    $datalimit  = 0;
    $result     = 0;
    
    $pagenumber_to_show = $_GET['pn'];
    
    for ($datalimit; $datalimit < $hit_row; $datalimit += $cfg['pagemax']) {
        $pagenumber++;
        if (isset($pagenumber_to_show)) {
            if ($pagenumber == $pagenumber_to_show) {
                $tag_array["tag"] = '<strong>';
                $tag_array["anchor"] = $pagenumber . '</strong>';
                $page_array[] = $tag_array;
                $array_key = count($page_array) == 0 ? 0 : count($page_array) - 1;
            } else {
                if ($pagenumber > $pagenumber_to_show + 5) {
                    $tag_array['tag']    = '-';
                    $tag_array['anchor'] =  '-';
                } elseif ($pagenumber < $pagenumber_to_show - 5) {
                    $tag_array['tag']    = '-';
                    $tag_array['anchor'] =  '-';
                } else {
                    $tag_array["tag"] = '<a href="' . $_SERVER['PHP_SELF'] . '?'
                                      . 'k=' . $keyword . '&amp;d=' . $date 
                                      . '&amp;p='  . $datalimit 
                                      . '&amp;pm=' . $cfg['pagemax'] 
                                      . '&amp;pn=' . $pagenumber 
                                      . '&amp;c='  . $case
                                      . '&amp;f='  . $topic_id . '">';
                    $tag_array["anchor"] = $pagenumber. '</a>';
                }
                $page_array[] = str_replace('-', '', $tag_array);
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

/* Page Flip Style 2 : Form */
function display_flip_form($field, $keyword, $date, $case, $hit_row) 
{
    global $cfg, $lang;
    $flip_form =<<<EOD

<form action="{$_SERVER['PHP_SELF']}" method="get">
<div class="flip-form">
<input type="hidden" name="k" value="{$keyword}" />
<input type="hidden" name="d" value="{$date}" />
<select class="resultchange" name="p" tabindex="6" onchange="this.form.submit()">
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
<input type="hidden" name="pm" value="{$cfg['pagemax']}" />
<input type="hidden" name="pn" value="" />
<input type="hidden" name="c" value="{$case}" />
<input type="hidden" name="f" value="{$field}" />
<noscript> 
<div class="noscript"><input type="submit" accesskey="f" tabindex="6" value="{$lang['flip']}" /></div>
</noscript>
</div>
</form>

EOD;
    return $flip_form;
}


/**
 * Previous Logs Navi Link
 */
function display_prev_logs_navi($target) 
{
    global $cfg, $lang, $cd, $rows, $http, $log_table, $info_table, $p_blog_path;
    
    if ($target == 'search') {
        $table = $log_table;
    } elseif ($target == 'files/search') {
        $table = $info_table;
    }
    $csql   = 'SELECT COUNT(`id`) FROM ' . $table . " WHERE `draft` = '0'";
    $cres   = mysql_query($csql);
    $ccount = mysql_fetch_row($cres);
    if (($ccount) && ($ccount[0] > $cfg['pagemax'])) {
        $prev_log_link = '<p id="prev-logs">'."\n".
                         '<a href="./search.php?k=&amp;p='.$cfg['pagemax'].'&amp;c=0&amp;pn=2&amp;d=all">'.
                         $lang['prev_logs'].
                         "</a>\n</p>";
    } else {
        $prev_log_link = '';
    }
    return $prev_log_link;
}



//================================================================
// MENU-BOX
//================================================================
/**
 * Menu List (Contents Navigation)
 */ 
function xhtml_menu($mode) 
{
    global $cd, $cfg, $lang, $add, $menu_list, $admin_dir, $http, $admin,
           $search_form, $table, $log_table, $info_table, $forum_table, $p_blog_path, $id, 
           $rss_feed, $rss_feed2,
           $admin_sess_menu, $content_menu, $search_form, $menu_middle, 
           $archive_by_date, $category_menu, $file_type_menu, 
           $recent_entries, $recent_comments, $recent_trackbacks, 
           $css_switch, $rss_button, $rss2_button, $menu_bottom, $plugin;

    require_once $cd . '/include/user_include/menu.inc.php';

    if (isset($_GET['add'])) {
        $add = $_GET['add'];
    }

    $request_uri = $http . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
    
    // if "ROOT PATH" is not specified yet, make up URI to go to "admin_top.php"
    if (($cfg['root_path'] == '/path/to/p_blog/') || (is_null($cfg['root_path']))) {
        $request_uri = $http . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        $uri = parse_url($request_uri);
        $uri = str_replace('root/root_login.php', 'admin_top.php', $uri);
        $admin_top_uri = $http . '://' . $uri['host'] . $uri['path'];
    } else {
        $admin_top_uri = $p_blog_path . $admin_dir . '/admin_top.php';
    }
    
    //////////////////// Admin Menu /////////////////////////////
    if ($admin == 'yes') {
        $admin_menu_title = '<h2 class="menu-box">'.
                            '<a href="' . $admin_top_uri . '">' . $lang['admin'] . "</a></h2>\n";
        // Normal Admin
        if ((isset($_SESSION['admin_login'])) && ($_SESSION['admin_login'] != '')) {
            $logout = '<div id="logout">'."\n".
                      '<a href="' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir . '/login.php?status=logout" accesskey="l">'.
                      $lang['logout'].
                      "</a>\n</div>\n";
                      
            // Post New Menu
            $admin_menu   = '<h3 id="add-new">'.$lang['add_new']."</h3>\n".
                            '<ul class="menu">'."\n";
            if (($request_uri . '?add=' . $add) == ($p_blog_path . $admin_dir . '/add.php?add=log')) {
                $admin_menu .= '<li class="cur-menu">'.$lang['log']. "</li>\n".
                               '<li class="menu"><a href="' . $p_blog_path . $admin_dir . '/add.php?add=bin" class="menu">'.
                               $lang['file'].
                               "</a></li>\n";
            } elseif (($request_uri . '?add=' . $add) == ($p_blog_path . $admin_dir . '/add.php?add=bin')) {
                $admin_menu .= '<li class="menu">'.
                               '<a href="' . $p_blog_path . $admin_dir . '/add.php?add=log" class="menu">'.
                               $lang['log'].
                               "</a></li>\n".
                               '<li class="cur-menu">'.$lang['file']. "</li>\n";
            } else {
                $admin_menu .= '<li class="menu">'.
                               '<a href="' . $p_blog_path . $admin_dir . '/add.php?add=log" class="menu">'.
                               $lang['log'] . "</a></li>\n".
                               '<li class="menu">'.
                               '<a href="' . $p_blog_path . $admin_dir . '/add.php?add=bin" class="menu">'.
                               $lang['file'] . "</a></li>\n";
            }
            $admin_menu .= "</ul>\n";
            
            // Modify / Delete Menu
            $menu_mod_del  = '<h3 id="edit">' . $lang['mod_del'] . "</h3>\n";
            
            // Session User Name
            $session_user  = $_SESSION['user_name'];
            $session_class = 'session-on';
            $draft_menu    = display_draft_num();
            
        // Root (MySQL user) Admin
        } elseif ((isset($_SESSION['root_admin_login'])) && ($_SESSION['root_admin_login'] != '')) {
            $logout = '<div id="logout">'.
                      '<a href="./root_login.php?status=logout">'.
                      $lang['logout'].'</a></div>';
            $admin_menu    = '';
            $menu_mod_del  = '';
            $session_user  = $_SESSION['root_user_name'];
            $session_class = 'session-on';
            $draft_menu    = '';
            
        // Out of session...
        } else {
            $logout        = '';
            $admin_menu    = '';
            $menu_mod_del  = '';
            $session_user  = $lang['none'];
            $session_class = 'session-off';
            $draft_menu    = '';
        }

        
        // Generate Admin Menu!
        $admin_sess_menu =<<<EOD
{$admin_menu_title}
<div class="menu">
<h2>{$lang['login_user']}</h2>
<p class="{$session_class}">{$session_user}</p>
{$logout}</div>
{$admin_menu}{$draft_menu}{$menu_mod_del}
EOD;

    } else {
        $admin_sess_menu = '';
    }
    
    //////////////////// Common Menu /////////////////////////////
    // Do not show content menu in Admin entrance
    if ((preg_match('/login.php/', $request_uri)) ||
        (preg_match('/root/', $request_uri))) {
        $content_menu = '';
    } else {
        if (isset($_GET['id'])) { 
            $id = addslashes($_GET['id']); 
        }
        
        // Begin Content Menu
        $content_menu = '<div id="content-menu">'."\n".
                        '<h2>'.$lang['contents'].'</h2>'."\n".
                        '<ul class="menu">'."\n";

        // User Defined Menu
        foreach ($menu_list as $key => $value) {
            if ((((isset($id)) && ($id != "") && 
                (($request_uri."?id=".$id) == $p_blog_path . preg_replace('/^.\//', '', $value)))) ||
                (((!isset($id)) && ($request_uri == $p_blog_path . preg_replace('/^.\//', '', $value)))) ||
                (((!isset($id)) && preg_match('/.*\/$/', $value) && ($request_uri == $p_blog_path . preg_replace('/^.\//', '', $value) . 'index.php')))) {
                $content_menu .= '<li class="cur-menu">'.$key."</li>\n";
            } elseif (preg_match('/^' . $http . ':\/\//', $value)) {
                $content_menu .= '<li class="menu"><a href="'.$value.'" class="menu">'.$key."</a></li>\n";
            } else { 
                $content_menu .= '<li class="menu"><a href="'.$cd.'/'.$value.'" class="menu">'.$key."</a></li>\n";
            }
        }
        // Forum Menu
        if ($cfg['comment_style'] == '1') {
            if (preg_match('/forum\/index.php/', $request_uri)) {
                $content_menu .= '<li class="cur-menu">'.$lang['forum']."</li>\n";
            } else { 
                $content_menu .= '<li class="menu"><a href="'.$cd.'/forum/index.php" class="menu">'.$lang['forum']."</a></li>\n";
            }            
        }
        // Feedback / Contact Menu
        if ($cfg['use_feedback_form'] == 'yes') {
            if (preg_match('/var\/feedback\/index.php/', $request_uri)) {
                $content_menu .= '<li class="cur-menu">'.$lang['feedback']."</li>\n";
            } else { 
                $content_menu .= '<li class="menu"><a href="'.$cd.'/var/feedback/index.php" class="menu">'.$lang['feedback']."</a></li>\n";
            }            
        }
        // Help Menu
        if (file_exists($cd . '/var/help/')) {
            if ((preg_match('/help\/index.php/', $request_uri)) && (empty($id))) {
                $content_menu .= '<li class="cur-menu">'.$lang['help']."</li>\n";
            } else { 
                $content_menu .= '<li class="menu"><a href="'.$cd.'/var/help/index.php" class="menu">'.$lang['help']."</a></li>\n";
            }            
        }
        // End Content Menu
        $content_menu .= "</ul>\n</div>\n";

    }

    //////////////////// Archive, Category, Recent Entries, Comments, Trackbacks Menu /////////////////////////////
    if ($mode == 'log') {
        $archive_by_date   = archive_by_date($log_table);
        $category_menu     = display_category($log_table);
        $file_type_menu    = '';
        include_once $cd . '/include/fnc_logs.inc.php';
        $recent_entries    = display_recent_entries();
        $recent_comments   = display_recent_comments();
        $recent_trackbacks = display_recent_trackbacks();
    } elseif ($mode == 'file') {
        $archive_by_date   = archive_by_date($info_table);
        $category_menu     = display_category($info_table);
        include_once $cd . '/files/include/fnc_files.inc.php';
        $file_type_menu    = display_type_menu();
        $recent_entries    = '';
        $recent_comments   = '';
        $recent_trackbacks = '';
    } elseif ($mode == 'forum') {
        $archive_by_date   = archive_by_date($forum_table);
        $category_menu     = '';
        $file_type_menu    = '';
        $recent_entries    = '';
        $recent_comments   = '';
        $recent_trackbacks = '';
    } else {
        $archive_by_date   = '';
        $category_menu     = '';
        $file_type_menu    = '';
        $recent_entries    = '';
        $recent_comments   = '';
        $recent_trackbacks = '';
    }
    
    // CSS Switch   
    $css_switch = display_css_switch();
    
    // RSS Feed
    if ($cfg['use_rss'] == 'yes') {

        $rss_id  = 'id="rss"' . 
                   ((($cfg['xml_version'] == '1.1') || ($cfg['xml_version'] == '1.1cn')) ? '' : ' name="rss"');
        $rss2_id = 'id="rss2"' . 
                   ((($cfg['xml_version'] == '1.1') || ($cfg['xml_version'] == '1.1cn')) ? '' : ' name="rss2"');
        
        $p_blog_path = str_replace('%7E', '~', $p_blog_path);
        
        if ($request_uri == $p_blog_path . $cfg['top_page']) {
            $rss_button  = '<a href="'.$cd.'/rss/recent.php" title="'.
                           $lang['rss_of_this_page'].'" '.$rss_id.'>'.$rss_feed.'</a>';
            $rss2_button = '<a href="'.$cd.'/rss/2.0.php" title="'.
                           $lang['rss_of_this_page'].'" '.$rss2_id.'>'.$rss_feed2.'</a>';
        } elseif ($request_uri == $p_blog_path . 'forum/topic.php') {
            $rss_button  = '<a href="'.$cd.'/rss/1.0.php?tid='.
                           sanitize(abs(intval($_GET['tid']))).
                           '" title="'.$lang['rss_of_this_page'].'" '.$rss_id.'>'.$rss_feed.'</a>';
            $rss2_button = '<a href="'.$cd.'/rss/2.0.php?tid='.
                           sanitize(abs(intval($_GET['tid']))).
                           '" title="'.$lang['rss_of_this_page'].'" '.$rss2_id.'>'.$rss_feed2.'</a>';
        } elseif ($request_uri == $p_blog_path . 'forum/index.php') {
            $rss_button  = '<a href="'.$cd.'/rss/1.0.php?f_index" title="'.
                           $lang['rss_of_this_page'].'" '.$rss_id.'>'.$rss_feed.'</a>';
            $rss2_button = '<a href="'.$cd.'/rss/2.0.php?f_index" title="'.
                           $lang['rss_of_this_page'].'" '.$rss2_id.'>'.$rss_feed2.'</a>';
        } elseif ($request_uri == $p_blog_path . 'article.php') {
            $rss_button = '<a href="'.$cd.'/rss/1.0.php?id='.
                           sanitize(abs(intval($id))).
                           '" title="'.$lang['rss_of_this_page'].'" '.$rss_id.'>'.$rss_feed.'</a>';
            $rss2_button = '<a href="'.$cd.'/rss/2.0.php?id='.
                           sanitize(abs(intval($id))).
                           '" title="'.$lang['rss_of_this_page'].'" '.$rss2_id.'>'.$rss_feed2.'</a>';
        } else {
            $rss_button = '';
            $rss2_button = '';
        }
    } else {
        $rss_button = '';
        $rss2_button = '';
    }
    
    // Load Menu Middle
    if (file_exists($cd . '/include/user_include/menu_middle.inc.php')) {
        require_once $cd . '/include/user_include/menu_middle.inc.php';
    } else {
        $menu_middle = '';
    }
    
    // Load Menu Bottom
    if (file_exists($cd . '/include/user_include/menu_bottom.inc.php')) {
        require_once $cd . '/include/user_include/menu_bottom.inc.php';
    } else {
        $menu_bottom = '';
    }
}



/**
 * Draft Number
 */
function display_draft_num() 
{
    global $cd, $admin_dir, $cfg, $lang, $log_table, $info_table;
    
    // SQL 1 (Check for Draft Article)
    $sql1 = "SELECT `id` FROM `{$log_table}` WHERE `draft` = '1'";
    $res1 = mysql_query($sql1);
    $row1 = mysql_num_rows($res1);
    if ($row1 <= 0) {
        $draft_log_num = ' ' . $lang['log'] . ' (0)';
    } else {
        $draft_log_num = '<a href="'.$cd.'/'.$admin_dir.'/draft_list.php"><strong> ' . $lang['log'] . ' (' . $row1 . ')</strong></a>';
    }
    
    // SQL 2 (Check for Draft Files)
    $sql2 = "SELECT `id` FROM `{$info_table}` WHERE `draft` = '1'";
    $res2 = mysql_query($sql2);
    $row2 = mysql_num_rows($res2);
    if ($row2 <= 0) {
        $draft_file_num = ' ' . $lang['file'] . ' (0)';
    } else {
        $draft_file_num = '<a href="'.$cd.'/'.$admin_dir.'/bin_draft_list.php"><strong> ' . $lang['file'] . ' (' . $row2 . ')</strong></a>';
    }
    
    // Presentation!
    $draft_menu = '<dl id="draft"><dt>' . $lang['draft'] . '</dt>'.
                  '<dd id="draft-log">' . $draft_log_num . '</dd>'.
                  '<dd id="draft-file">' . $draft_file_num . '</dd>'.
                  '</dl>';
    return $draft_menu;
}


/**
 * Archive Menu
 */
function archive_by_date($table) 
{
    global $cfg, $lang, $log_table, $info_table, $forum_table;
    
    $sql = '';
    if ($table == $log_table) {
        $sql = "SELECT DATE_FORMAT(`date`, '%Y-%m') as `date` ".
               "FROM `{$log_table}` WHERE `draft` = '0' GROUP BY `date` ORDER BY `date`";
    } elseif ($table == $info_table) {
        $sql = "SELECT DATE_FORMAT(`bindate`, '%Y-%m') as `bindate` ".
               "FROM `{$info_table}` WHERE `draft` = '0' GROUP BY `bindate` ORDER BY `bindate`";
    } elseif ($table == $forum_table) {
        $sql = "SELECT DATE_FORMAT(`date`, '%Y-%m') as `date` ".
               "FROM `{$forum_table}` WHERE `trash` = '0' GROUP BY `date` ORDER BY `date`";
    }
    if ($cfg['date_order_desc'] == 'yes') {
        $sql.= ' DESC';
    }
    
    $res = mysql_query($sql);
    $archive_list = '';
    while ($date_row = mysql_fetch_row($res)) {
        $year_month = "".$date_row[0]."";
        $archive_list .= '<option>'.$year_month."</option>\n";
    }
    // Presentation!    
    $archive_by_date =<<<EOD
<form id="archives" action="./search.php" method="get">
<div>
<input type="hidden" name="k" value="" />
<input type="hidden" name="p" value="0" />
<input type="hidden" name="c" value="0" />
<input type="hidden" name="pn" value="1" />
<select name="d" tabindex="3" onchange="if(document.forms.archives.d.value != '-'){this.form.submit();}" title="{$lang['show_archives']}">
<option value="-"  selected="selected">{$lang['archives']}</option>
<option value="-" disabled="disabled">- - -</option>
{$archive_list}<option value="-" disabled="disabled">- - -</option>
<option value="all"> {$lang['all_data']} </option>
</select>
<noscript>
<div class="noscript">
<input type="submit" accesskey="s" tabindex="3" value="Go" />
</div>
</noscript>
</div>
</form>
EOD;
    return $archive_by_date;
}


/**
 * Category Menu Functions
 * @author : nakamuxu
 */
/* generate name array */
function cat_name_array($table) 
{
    global $cfg, $log_table, $info_table, $cat_num, $row;
    
    if ($table == $log_table) {
        $sql     = "SELECT `category` FROM `{$log_table}` WHERE `draft` = '0'";
        $cat_num = $cfg['show_cat_num'];
    } elseif ($table == $info_table) {
        $sql     = "SELECT `bin_category` FROM `{$info_table}` WHERE `draft` = '0'";
        $cat_num = $cfg['show_bin_cat_num'];
    }     
    
    $res = mysql_query($sql);
    $rowArray = array();
    while ($row = mysql_fetch_array($res)) {
        $row = convert_to_utf8($row);
        if ($table == $log_table) {
            $cat_row = $row['category'];
        } elseif ($table == $info_table) {
            $cat_row = $row['bin_category'];
        }
        $token = strtok($cat_row, ",");
        while ($token) {
            array_push($rowArray, trim($token));
            $token = strtok(",");
        }
    }
    if ($cat_num == 'yes') {
        $rowArray = array_count_values($rowArray);
        ksort($rowArray, SORT_STRING);
    } else {
        $rowArray = array_unique($rowArray);
        sort($rowArray, SORT_STRING);
    }
    return $rowArray;
}


function display_category($table)
{
    global $cfg, $lang, $cat_num, $log_table, $info_table;
    
    $query_string   = htmlspecialchars($_SERVER['QUERY_STRING']);
    $cat_name_array = cat_name_array($table);

    if ($table == $log_table) {
        $show_categories = $cfg['show_categories'];
        $category_style  = $cfg['category_style'];
    } elseif ($table == $info_table) {
        $show_categories = $cfg['show_bin_categories'];
        $category_style  = $cfg['bin_category_style'];
    } else {
        $show_categories = '';
        $category_style  = '';
    }
    
    $category_list = '';
    if ($show_categories == 'yes') {
        if ($category_style == 1) { // Form Style
            if ($cat_num == 'yes') {
                foreach ($cat_name_array as $str => $num) {
                    $category_list .= '<option value="'.htmlspecialchars($str).'">'.
                                      htmlspecialchars($str).' ('.$num.")</option>\n";
                }
            } else {
                foreach ($cat_name_array as $str) { 
                    $category_list .= '<option>'.htmlspecialchars($str)."</option>\n";
                }
            }
            //////////////// Presentation! /////////////////
            $category_menu =<<<EOD
<form id="category" action="./category.php?{$query_string}" method="get">
<div>
<select name="k" tabindex="4" onchange="if(document.forms.category.k.value != '-'){this.form.submit();}" title="{$lang['category']}">
<option value="-" selected="selected">{$lang['category']}</option>
<option value="-" disabled="disabled">- - -</option>
{$category_list}</select>
<noscript> 
<div class="noscript">
<input type="submit" accesskey="s" tabindex="4" value="Go" />
</div>
</noscript>
</div>
</form>
EOD;
        } elseif ($category_style == 2) { // List Style
            if ($cat_num == 'yes') {
                foreach ($cat_name_array as $str => $num) {
                     $category_list .= '<li><a href="./category.php?k=' . rawurlencode($str) .
                                       '">' .htmlspecialchars($str). ' (' .$num. ")</a></li>\n";
                }
            } else {
                foreach ($cat_name_array as $str) {
                     $category_list .= '<li><a href="./category.php?k=' . rawurlencode($str) .
                                       '">' .htmlspecialchars($str). "</a></li>\n";
                }
            }
            //////////////// Presentation! /////////////////
            $category_menu =<<<EOD

<div class="menu">
<h2>{$lang['category']}</h2>
<ul>
{$category_list}</ul>
</div>
EOD;
        } else { // Tag Style (Original code by Hetima)
            $ul_start='<ul>'; $ul_end='</ul>';
            $tag_list = '';
            if (count($cat_name_array) >= 8) {
                $ul_start = '<div class="tag-list">'."\n"; $ul_end='</div>';
                $tag_level_4 = 100;
                $tag_level_3 = 10;
                $tag_level_2 = 2;
                $tag_level_1 = 0;
                //calc
                $i = 0;
                $s_array = array_values($cat_name_array);
                rsort($s_array, SORT_NUMERIC);
                $tag_level_4 = $s_array[1];
                $tag_level_3 = $s_array[3];
                $tag_level_2 = $s_array[7];
                foreach ($cat_name_array as $str => $num) {
                    if ($num >= $tag_level_4) {
                        $span_prop = '4';
                    } elseif ($num >= $tag_level_3) {
                        $span_prop = '3';
                    } elseif ($num >= $tag_level_2) {
                        $span_prop = '2';
                    } else {
                        $span_prop = '1';
                    }
                    $tag_list .= '<span class="tag-level-'.$span_prop.'">'
                              .  '<a href="./category.php?k='.rawurlencode($str).'" '
                              . 'title="'.htmlspecialchars($str).'('.$num.')">'
                              .  htmlspecialchars($str)."</a></span> \n";
                }
            } else {
                foreach ($cat_name_array as $str => $num) {
                    $tag_list .= '<li><a href="./category.php?k='
                              . rawurlencode($str) . '">' .htmlspecialchars($str). ' ('
                              . $num . ")</a></li>\n";
                }
            }
            //////////////// Presentation! /////////////////
            $category_menu =<<<EOD
<div class="menu">
<h2>Tags</h2>
{$ul_start}{$tag_list}{$ul_end}
</div>
EOD;
        }
    } else {
        $category_menu = '';
    }
    
    return $category_menu;
}


/**
 * CSS Switcher
 */
function display_css_switch()
{
    global $cd, $cfg, $lang, $styles, $request_uri;
    
    if (($cfg['use_css_switch'] == 'yes') &&
        (!preg_match('/draft/', $request_uri))) {
        $query_string = htmlspecialchars($_SERVER['QUERY_STRING']);
        $css_list = '';
        foreach ($styles as $key => $value) {
            $css_list .= '<option value="'.$value.'">'.$key."</option>\n";
        }
        //////////////// Presentation! /////////////////
        $css_switch =<<<EOD
<form id="css-form" action="{$_SERVER['PHP_SELF']}?{$query_string}" method="post">
<div>
<select tabindex="5" name="style_num" onchange="if(document.forms.css-form.style_num.value != ''){this.form.submit();}">
<option value="">{$lang['switch_css']}</option>
<option value="" disabled="disabled">- - -</option>
{$css_list}<option value="off">{$lang['css_off']}</option>
</select>
<noscript>
<div class="noscript">
<input type="submit" accesskey="s" tabindex="5" value="Go" />
</div>
</noscript>
</div>
</form>
EOD;
    } else {
        $css_switch = '';
    }
    return $css_switch;
}


//================================================================
// FOOTER-BOX
//================================================================

/**
 * Footer Style 1 : Default Footer
 */
function display_footer($cd) 
{
    global $cfg, $begin_time_str;
    
    $gt = display_gt($begin_time_str);
    
    //////////////// Presentation! ///////////////// 
    $footer_content =<<<EOD
<address>
<a href="http://pbx.homeunix.org/p_blog/index.php" title="Powered by P_BLOG">
<img src="{$cd}/images/p_blog_logo.png" width="88" height="31" alt="P_BLOG" class="logo" />    
</a>
{$cfg['copyright']}. All rights reserved. 
</address>
<script type="text/javascript">js_email_link();</script>
<noscript>
  <div class="no-js-email">
    &lt;JavaScript must be &quot;on&quot; to display the email address.&gt;
  </div>
</noscript>
{$gt}
EOD;
    return $footer_content;

}



/**
 * Footer Style 2 : with P_BLOG Original Validator Logos
 */
function display_valid_footer($style_num, $style, $cd) 
{
    global $cd, $cfg, $begin_time_str, $p_blog_path, $lang;
    switch ($cfg['xml_version']) {
        case '1.1':
            $xhtml_ver = 'XHTML 1.1';
            break;
        case '1.1cn':
            $xhtml_ver = 'XHTML 1.1';
            break;
        default:
            $xhtml_ver = 'XHTML 1.0';
            break;
    }
    if ($cfg['debug_mode'] == 'on') {
        if (!empty($cfg['custom_ahl_path'])) {
            $ahl_path = $cfg['custom_ahl_path'];
        } else {
            $ahl_path = 'http://openlab.ring.gr.jp/k16/htmllint/htmllint.cgi';
        }
    } else {
        $ahl_path = 'http://openlab.ring.gr.jp/k16/htmllint/htmllint.cgi';
    }
    
    $gt = display_gt($begin_time_str);
    
    if (file_exists($cd . '/include/user_include/user_footer.inc.php')) {
        require_once $cd . '/include/user_include/user_footer.inc.php';
    } else {
        $user_footer = '';
    }
    
    //////////////// Presentation! /////////////////
    $footer_content =<<<EOD
<p id="validators">
<a href="http://validator.w3.org/check/referer" title="{$lang['validate_xhtml']}">
{$xhtml_ver}
</a>
<a href="http://jigsaw.w3.org/css-validator/validator?uri={$p_blog_path}styles/{$style_num}/{$style}.css" title="{$lang['validate_css']}">
CSS
</a>
<a href="{$ahl_path}?ViewSource=on" title="{$lang['validate_ahl']}">
AHL
</a>
</p>
{$user_footer}
<address>{$cfg['copyright']}. All rights reserved. Powered by <a href="http://pbx.homeunix.org/p_blog/index.php" title="P_BLOG project">P_BLOG</a>.</address>
{$gt}
EOD;
    return $footer_content;
}


/**
 * Footer Style 3 : with W3C Original Validator Logos
 */
function display_w3c_valid_footer($style_num, $style, $cd) 
{
    global $cd, $cfg, $begin_time_str, $admin;
    switch ($cfg['xml_version']) {
        case '1.1':
            $logo_src = 'valid-xhtml11.png';
            $logo_alt = 'Valid XHTML1.1';
            break;
        case '1.1cn':
            $logo_src = 'valid-xhtml11.png';
            $logo_alt = 'Valid XHTML1.1';
            break;
        default:
            $logo_src = 'valid-xhtml10.png';
            $logo_alt = 'Valid XHTML1.0';
            break;
    }
    
    $gt = display_gt($begin_time_str);

    if (file_exists($cd . '/include/user_include/user_footer.inc.php')) {
        require_once $cd . '/include/user_include/user_footer.inc.php';
    } else {
        $user_footer = '';
    }
    
    //////////////// Presentation! /////////////////    
    $footer_content =<<<EOD
<p id="w3c-validators">
<a href="http://validator.w3.org/check/referer">
<img src="{$cd}/images/{$logo_src}" width="88" height="31" alt="{$logo_alt}" class="validator" />
</a>
<a href="http://jigsaw.w3.org/css-validator/validator?uri=http://{$_SERVER['HTTP_HOST']}{$cfg['root_path']}styles/{$style_num}/{$style}.css">
<img src="{$cd}/images/valid-css.png" width="88" height="31" alt="Valid CSS" class="validator" />
</a>
<a href="http://www.w3.org/Style/CSS/Buttons">
<img src="{$cd}/images/mwcts.png" width="88" height="31" alt="CSS" class="validator" />
</a>
</p>
{$user_footer}<address>{$cfg['copyright']}. All rights reserved. Powered by <a href="http://pbx.homeunix.org/p_blog/index.php" title="P_BLOG project">P_BLOG</a>.</address>
{$gt}
EOD;
    return $footer_content;
}


/**
 * Footer Style 4 : User Custom Footer
 */
function display_user_footer($admin) 
{
    global $cd, $cfg, $begin_time_str, $user_footer;
    
    $gt = display_gt($begin_time_str);
    
    if (file_exists($cd . '/include/user_include/user_footer.inc.php')) {
        require_once $cd . '/include/user_include/user_footer.inc.php';
    } else {
        $user_footer = '';
    }
    
    //////////////// Presentation! /////////////////
    $footer_content =<<<EOD
{$user_footer}
{$gt}
EOD;
    return $footer_content;
}



/**
 * Page Generation Time
 * @author : nakamuxu
 */
function microtime_to_microseconds($microtime) 
{
    $tmp_array = explode(" ", $microtime);
    return (((float)$tmp_array[0]) + ((float)$tmp_array[1]));
}

function between_microseconds($begin_str, $finish_str) 
{
    $begin_microseconds = microtime_to_microseconds($begin_str);
    $finish_microseconds = microtime_to_microseconds($finish_str);
    return ($finish_microseconds - $begin_microseconds);
}

function display_gt($begin_time_str) 
{
    global $cfg;
    if ($cfg['show_generation_time'] == 'yes') {
        $finish_time_str = microtime();
        $lapsed_time = between_microseconds($begin_time_str, $finish_time_str);
        $gt = '<p id="page-generation">Page generation : '.number_format($lapsed_time, 4).' seconds.</p>';
    } else {
        $gt = '';
    }
    return $gt;
}


//================================================================
// SECURITY
//================================================================
/**
 * Against XSS
 * @author : nakamuxu
 */
function against_xss() 
{
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            foreach ($_POST as $key => $value) {
                if (is_array($value)) {
                    array_walk($value, "htmlspecialchars");
                    $_REQUEST[$key] = $value;
                } else {
                    $_REQUEST[$key] = htmlspecialchars(trim($value), ENT_QUOTES);
                }
            }
            break;
        case 'GET':
            foreach ($_GET as $key => $value) {
                if (is_array($value)) {
                    array_walk($value, "htmlspecialchars");
                    $_REQUEST[$key] = $value;
                } else {
                    $_REQUEST[$key] = htmlspecialchars(trim($value), ENT_QUOTES);
                }
            }
            break;
    }
}

// For Main Log
// Insert strings with XHTML tags
function insert_tag_safe($str) 
{
    if (get_magic_quotes_gpc() == 1) {
        return trim($str);
    } else {
        return addslashes(trim($str));
    }
}


// For FORUM
// Insert strings without XHTML tags
function insert_safe($str) 
{
    if (get_magic_quotes_gpc() == 1) {
        return strip_tags(trim($str));
    } else {
        return addslashes(strip_tags(trim($str)));
    }
}

// sanitize the string
function sanitize($str) 
{
    return htmlspecialchars($str);
}


/**
 * Validate E-Mail Format
 *
 */
function valid_email($mail) 
{
    if (!preg_match('/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $mail)) {
        return 1;
    } else {
        return 0;
    }
}


/**
 * New lines to Paragraph
 *
 */
function nl2p($str)
{
    $lines = split("\n", $str);
    for ($i = 0, $j = count($lines); $i < $j; $i++) {
        $lines[$i] = $lines[$i];
        // Convert URI
        if (preg_match('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)(\()(.+?)(\))/', $lines[$i])) {
            $uri_title = trim(preg_replace('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)(\()(.+?)(\))/', '$4', $lines[$i]));
            $res_uri   = trim(preg_replace('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)(\()(.+?)(\))/', '$2', $lines[$i]));
            $lines[$i] = '<a href="'.$res_uri.'" class="ex-ref">'.$uri_title.'</a>';
        } else {
            $lines[$i] = preg_replace('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)/', '$1<a href="$2" rel="nofollow">$2</a>', $lines[$i]);
        }
    }
    $str = join("\n", $lines);
    
    // Format line breaks and paragraphs
    $str = str_replace("\r\n", "\n",       $str);
    $str = str_replace("\n\n", "</p><p>",  $str);
    $str = str_replace("\n",   "<br />",   $str);
    
    // Convert <pre> tags
    $str = str_replace('[pre]', '</p><pre>', $str);
    $str = str_replace('[/pre]', '</pre><p>', $str);

    // Quote box coloring
    $str = str_replace('[q1]', '</p><blockquote class="quote1"><p>', $str);
    $str = str_replace('[/q1]', '</p></blockquote><p>', $str);
    $str = str_replace('[q2]', '</p><blockquote class="quote2"><p>', $str);
    $str = str_replace('[/q2]', '</p></blockquote><p>', $str);
    $str = str_replace('[q3]', '</p><blockquote class="quote3"><p>', $str);
    $str = str_replace('[/q3]', '</p></blockquote><p>', $str);
    
    $str = str_replace('<p><br />', '<p>', $str);
    $str = str_replace('</p><br />', '</p>', $str);
    $str = str_replace('<br /></p>', '</p>', $str);
    $str = str_replace('<pre><br />', '<pre>', $str);
    $str = str_replace('<br /></pre>', '</pre>', $str);
    $str = str_replace('<p><pre>', '<pre>', $str);
    $str = str_replace('</pre></p>', '</pre>', $str);
    $str = str_replace('<p><p>', '<p>', $str);
    $str = str_replace('</p></p>', '</p>', $str);
    
    // Put all together into the paragraph
    $str = '<p>' . $str . "</p>\n";
    
    // Remove the bad patterns
    $str = str_replace('<p></p>', '', $str); // Remove
    $str = str_replace('<pre></pre>', '', $str); // Remove
    $str = str_replace('<br /><br />', '', $str); // Remove
    
    // Clean up the XHTML code
    $str = str_replace("><", ">\n<", $str);

    return $str;
}


/**
 * Convert Text to XHTML automatically
 */
function text2xhtml($str) {
    if (!preg_match('/^<\/?(?:h1|h2|h3|h4|h5|h6|table|ol|dl|ul|menu|dir|p|pre|center|form|fieldset|blockquote|address|div|hr|img)/', $str)) {
        $lines = split("\n", $str);
        for ($i = 0, $j = count($lines); $i < $j; $i++) {
            $lines[$i] = $lines[$i];
            // Convert Image URI
            if (preg_match('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)(.png|.jpg|.gif)(\()(.+?)(\))/', $lines[$i])) {
                $img_alt   = trim(preg_replace('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)(.png|.jpg|.gif)(\()(.+?)(\))/', '$5',   $lines[$i]));
                $img_uri   = trim(preg_replace('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)(.png|.jpg|.gif)(\()(.+?)(\))/', '$2$3', $lines[$i]));
                $img_info  = @getimagesize($img_uri);
                $lines[$i] = $img_uri;
                $lines[$i] = '<img src="' . $img_uri . '" width="'.$img_info[0].'" height="'.$img_info[1].'" alt="'.$img_alt.'" />';
            }
            // Convert URI
            if (preg_match('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)(\()(.+?)(\))/', $lines[$i])) {
                $uri_title = trim(preg_replace('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)(\()(.+?)(\))/', '$4', $lines[$i]));
                $res_uri   = trim(preg_replace('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)(\()(.+?)(\))/', '$2', $lines[$i]));
                $lines[$i] = '<a href="'.$res_uri.'" class="ex-ref">'.$uri_title.'</a>';
            } else {
                $lines[$i] = preg_replace('/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\%]+)/', '$1<a href="$2">$2</a>', $lines[$i]);
            }            
        }
        $str = join("\n", $lines);

        // Format line breaks and paragraphs
        $str = str_replace("\r\n", "\n",       $str);
        $str = str_replace("\n\n", "</p><p>",  $str);
        $str = str_replace("\n",   "<br />",   $str);

        // Put all together into the paragraph
        $str = '<p>' . $str . "</p>\n";
        
        // Convert bad patterns
        $str = preg_replace('/<(p|pre|table|tr|th|td|ul|ol|li)><br \/>/', '<$1>', $str);
        $str = preg_replace('/<br \/><\/(p|pre|table|tr|th|td|ul|ol|li)>/', '</$1>', $str);
        $str = preg_replace('/<br \/><(p|pre|table|tr|th|td|ul|ol|li)>/', '<$1>', $str);
        $str = preg_replace('/<\/(p|pre|table|tr|th|td|ul|ol|li)><br \/>/', '</$1>', $str);
        $str = preg_replace('/<p><p class="read-more">/', '<p class="read-more">', $str);
        $str = str_replace('<!-- more --><br />', '<!-- more -->', $str);
        $str = str_replace('<br /><!-- more -->', '<!-- more -->', $str);
        $str = preg_replace('/<p><(p|pre|table|ul|div|blockquote|form)/', '<$1', $str);
        $str = preg_replace('/<\/(p|pre|table|ul|div|blockquote|form)><\/p>/', '</$1>', $str);
        $str = preg_replace('/<(p|pre|table|ul|ol)><\/(p|pre|table|ul|ol)>/', '', $str);
        $str = str_replace('<br /><br />', '', $str);
        
        // Clean up the XHTML code
        $str = str_replace("><", ">\n<", $str);
    }
    return $str;
}

/**
 * Automatic XHTML Markup
 * @author   : kaz
 * @based on : xhtml_auto_tag() by Ohnishi
 */
function xhtml_auto_markup($var) {
    
    global $cd;
    
    $var = str_replace("\r\n", "\n", $var);
    $var = str_replace("\n\n\n", "\n\n", $var);
    $row = split("\n\n", $var);
    $i = 0;
    foreach ($row as $str) {
        if (!preg_match('/^<\/?(?:h1|h2|h3|h4|h5|h6|table|ol|dl|ul|menu|dir|p|pre|center|form|fieldset|blockquote|address|div|hr)/', $str)) {
            $str = preg_replace('/\r?\n?<br \/>\r?\n?/', "\n", $str);
            $str = str_replace("\n", '<br />', $str);
            $row[$i] = '<p>' . $str . '</p>';
        } elseif (preg_match('/^<(?:blockquote|div|p)/', $str)){
            $str = preg_replace('/\r?\n?<br \/>\r?\n?/', "\n", $str);
            $str = str_replace("\n", '<br />', $str);
        } 
        // Remove "<br />" from textarea pre
        $str = preg_replace('/((?:\G|<(textarea|pre)>)[^<]*?)<br\/>/', '$1', $str);
        $i++;
    }
    $var = join("", $row);
        
    // Remove block in block
    $var = preg_replace('/<(p|pre|table|ul|ol)><\/(p|pre|table|ul|ol)>/', '', $var);
    
    // Remove break after break and paragraph
    $var = str_replace('<br /><br />', '<br />', $var);
    $var = str_replace('<p><br />', '<p>', $var);
    $var = str_replace('</p></p>', '</p>', $var);
    
    // Modify bad patterns
    $var = preg_replace('/<p><!-- ?more ?--><br \/>/is', '<!-- more --><p>', $var);

    $var = str_replace("><", ">\n<", $var);
    
    return $var;
}


/**
 * Convert Text to XHTML automatically
 * @author: Ohnishi
 * @modify: kaz
 */
function xhtml_auto_tag($var) {
    $row = preg_split('/\r?\n\r?\n/', $var);
    $i = 0;
    foreach ($row as $str) {
        if (!preg_match('/^<\/?(?:h1|h2|h3|h4|h5|h6|table|ol|dl|ul|menu|dir|p|pre|center|form|fieldset|blockquote|address|div|hr)/',$str)) {
            $str = preg_replace('/\r?\n?<br \/>\r?\n?/', "\n", $str);
            $str = nl2br($str);
            $row[$i] = '<p>' . $str . '</p>';
        } elseif (preg_match('/^<(?:blockquote|div|p)/',$str)){
            $str = preg_replace('/\r?\n?<br \/>\r?\n?/', "\n", $str);
            $str = nl2br($str);
        } 
        // Remove "<br />" from textarea pre
        $str = preg_replace('/((?:\G|<(textarea|pre)>)[^<]*?)<br\/>/','$1',$str);
        $i++;
    }
    $var = implode("", $row);
    
    // Remove block in block
    $var = preg_replace('/<(p|pre|table|ul|ol)><\/(p|pre|table|ul|ol)>/', '', $var);
    // Remove break break
    $var = str_replace('<br /><br />', '', $var);
    $var = str_replace("><", ">\n<", $var);
    return $var;
}




/**
 * User's Comment Type
 *
 */
function comment_class() {
    global $comment_class, $row;
    
    $list = '';
    $class_order = array_values($comment_class);
    foreach ($class_order as $key => $value) {
        if ((!empty($row['color'])) && ($key == $row['color'])) {
            $checked = 'checked="checked" ';
        } elseif ($key == '0') {
            $checked = 'checked="checked" '; 
        } else {
            $checked = '';
        }
        $list .= <<<EOD
<input type="radio" accesskey="{$key}" tabindex="7" name="color" value="{$key}" id="c{$key}" {$checked}/><label for="c{$key}"><span id="comment-type-{$key}">{$value}</span></label>

EOD;
    }
    
    return $list;
}


/**
 * Smiley
 *
 */
function smiley($str)
{
    global $cd, $cfg, $http;
    $smiley_path = $http.'://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].'images/smiley/';
    if (($cfg['enable_smiley'] == 'yes') && (file_exists($cd . '/images/smiley/'))) {
        $str = str_replace(':-)',  '<img src="'.$smiley_path.'smile.png" width="18" height="18" alt="(Smile)" />', $str);
        $str = str_replace(';-)',  '<img src="'.$smiley_path.'wink.png" width="18" height="18" alt="(Wink)" />',  $str);
        $str = str_replace(':-D',  '<img src="'.$smiley_path.'laugh.png" width="18" height="18" alt="(Laugh)" />', $str);
        $str = str_replace(':-!',  '<img src="'.$smiley_path.'foot_in_mouth.png" width="18" height="18" alt="(Foot in mouth)" />', $str);
        $str = str_replace(':-(',  '<img src="'.$smiley_path.'frown.png" width="18" height="18" alt="(Frown)" />', $str);
        $str = str_replace('=-o',  '<img src="'.$smiley_path.'gasp.png" width="18" height="18" alt="(Gasp)" />', $str);
        $str = str_replace('8-)',  '<img src="'.$smiley_path.'cool.png" width="18" height="18" alt="(Cool)" />', $str);
        $str = str_replace(':-P',  '<img src="'.$smiley_path.'tongue.png" width="18" height="18" alt="(Tongue)" />', $str);
    }
    return $str;
}

function smiley_button()
{
    global $cfg, $cd;

    if (($cfg['enable_smiley'] == 'yes') && (file_exists($cd . '/images/smiley'))) {
    
        $smiley_list =<<<EOD
<a href="{$_SERVER['PHP_SELF']}" onclick="smiley(':-)'); return false;" onkeypress="return false;">
<img src="{$cd}/images/smiley/smile.png" width="18" height="18" alt="(Smile)" />
</a>
<a href="{$_SERVER['PHP_SELF']}" onclick="smiley(';-)'); return false;" onkeypress="return false;">
<img src="{$cd}/images/smiley/wink.png" width="18" height="18" alt="(Wink)" />
</a>
<a href="{$_SERVER['PHP_SELF']}" onclick="smiley(':-D'); return false;" onkeypress="return false;">
<img src="{$cd}/images/smiley/laugh.png" width="18" height="18" alt="(Laugh)" />
</a>
<a href="{$_SERVER['PHP_SELF']}" onclick="smiley(':-!'); return false;" onkeypress="return false;">
<img src="{$cd}/images/smiley/foot_in_mouth.png" width="18" height="18" alt="(Foot in mouth)" />
</a>
<a href="{$_SERVER['PHP_SELF']}" onclick="smiley(':-('); return false;" onkeypress="return false;">
<img src="{$cd}/images/smiley/frown.png" width="18" height="18" alt="(Frown)" />
</a>
<a href="{$_SERVER['PHP_SELF']}" onclick="smiley('=-o'); return false;" onkeypress="return false;">
<img src="{$cd}/images/smiley/gasp.png" width="18" height="18" alt="(Gasp)" />
</a>
<a href="{$_SERVER['PHP_SELF']}" onclick="smiley('8-)'); return false;" onkeypress="return false;">
<img src="{$cd}/images/smiley/cool.png" width="18" height="18" alt="(Cool)" />
</a>
<a href="{$_SERVER['PHP_SELF']}" onclick="smiley(':-P'); return false;" onkeypress="return false;">
<img src="{$cd}/images/smiley/tongue.png" width="18" height="18" alt="(Tongue)" />
</a>
<!--
<a href="{$_SERVER['PHP_SELF']}" onclick="smiley('[pre][/pre]'); return false;" onkeypress="return false;">
<input type="button" tabindex="7" accesskey="p" value="pre" />
</a>
-->
EOD;
    } else {
        $smiley_list = '';
    }
    return $smiley_list;
}


/**
 * Convert byte to KB or MB
 *
 * バイト(Byte)値をメガバイト(MB)値に変換して返します。
 *
 * @package P_BLOG
 * @param   int    $str
 * @return  string $str
 * @since   2005-05-16 18:49:00 updated: 2005-11-06 13:39:35
 */
function toMB($str)
{
    $str = round(stripslashes($str) / 1024, 1); // Byte => KB 
    if ($str > 1024) {
        $str = round($str / 1024, 1) . ' MB';
    } else {
        $str = $str . ' KB';
    }
    return $str;
}


// Check comment & trackback host (20060530)
// source: http://www.rbl.jp/bbstbspam.html
function check_spammer() 
{
    $re_flg = 0;
    $ip = getenv('REMOTE_ADDR');
    if ($ip == '127.0.0.1') {
        $ip = '$HTTP_X_FORWARDED_FOR';
    }
        
    if (preg_match('/^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$/', $ip, $matches)) {
        $q1 = $matches[1];
        $q2 = $matches[2];
        $q3 = $matches[3];
        $q4 = $matches[4];
        $ip = "{$q4}.{$q3}.{$q2}.{$q1}";
    } else {
    }

    // check RBL
    $i = 0;
    $check_list = array('.niku.2ch.net');
//    $check_list = array(".list.dsbl.org", ".all.rbl.jp");
    while ($i < count($check_list)) {
        $check = $ip . $check_list[$i];
        $i ++;
        $result = gethostbyname($check);
        if ($result != $check) {
            $re_flg = 1;
            break;
        } else {
        }
    }
    return $re_flg;
}

// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . "/index.php");
}
?>